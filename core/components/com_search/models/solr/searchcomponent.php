<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 * @since     2.1.4
 */

namespace Components\Search\Models\Solr;

use Hubzero\Database\Relational;
use Hubzero\Database\Rows;
use Components\Search\Helpers\DiscoveryHelper;
use \Solarium\Exception\HttpException;
use Component;

require_once Component::path('com_search') . '/helpers/discoveryhelper.php';

/**
 * Database model for search components
 *
 * @uses  \Hubzero\Database\Relational
 */
class SearchComponent extends Relational
{
	/**
	 * Table namespace
	 *
	 * @var  string
	 */
	protected $namespace = 'solr_search';

	/**
	 * Automatic fields to populate every time a row is created
	 *
	 * @var  array
	 */
	public $initiate = array(
		'created'
	);

	/**
	 * Hubzero\Search\Adapters\SolrIndexAdapter
	 *
	 * @var  object
	 */
	private $searchIndexer = null;

	/**
	 * Discover searchable components
	 *
	 * @return  object  Hubzero\Database\Rows
	 */
	public function getNewComponents()
	{
		$existing = self::all()->rows()->fieldsByKey('name');
		$components = DiscoveryHelper::getSearchableComponents($existing);
		$newComponents = new Rows();
		foreach ($components as $component)
		{
			$newItem = self::blank()->set('name', $component);
			$newComponents->push($newItem);
		}
		return $newComponents;
	}

	/**
	 * Check if any override views for solr results exist in the component.
	 * @param string $layout name of view file used to format individual results.
	 * @param string $name name of the view directory containing the layout file.
	 * @return mixed returns array of values if file found, false if not
	 */
	public function getViewOverride($layout = 'solr', $name = 'search')
	{
		$base_path = Component::path($this->get('name')) . '/site';
		$override_path = Component::canonical($this->get('name'));
		$templatePath = '';
		if (App::has('template'))
		{
			$templatePath = App::get('template')->path . '/html';
		}
		$fileName = $layout . '.php';
		$overrideFile = $templatePath . '/' . $override_path . '/' . $name . '/' . $fileName;
		$viewFile = $base_path . '/views/' . $name . '/tmpl/' . $fileName;
		if (file_exists($viewFile) || file_exists($overrideFile))
		{
			return compact('layout', 'name', 'base_path', 'override_path');
		}
		return false;
	}

	/**
	 * Add results to solr index
	 *
	 * @param   int    $offset  where to begin the database query
	 * @return  mixed  array of values if more records
	 */
	public function indexSearchResults($offset)
	{
		$params = Component::params('com_search');
		$batchSize = $params->get('solr_batchsize', 1000);
		$commitWithin = $params->get('solr_commit', 50000);
		$model = $this->getSearchableModel();
		$newQuery = $this->getSearchIndexer();

		$modelResults = $model::searchResults($batchSize, $offset);

		if (count($modelResults) > 0)
		{
			foreach ($modelResults as $result)
			{
				$searchResult = $result->searchResult();
				if ($searchResult)
				{
					$newQuery->index($searchResult, true, $commitWithin, $batchSize);
				}
			}
			$results = array(
				'limit'  => $batchSize,
				'offset' => $offset + $batchSize,
			);
			$error = $newQuery->finalize();
			if ($error)
			{
				$results['error'] = $error;
			}
			return $results;
		}
		return false;
	}

	/**
	 * Get total record count of component
	 *
	 * @return  int  number of batches to retrieve
	 */
	public function getSearchCount()
	{
		$model = $this->getSearchableModel();
		$params = Component::params('com_search');
		$batchSize = $params->get('solr_batchsize');
		$batches = ceil($model::searchTotal() / $batchSize);
		return $batches;
	}

	/**
	 * Get model that contains the searchable records
	 *
	 * @return  object  Interface Hubzero\Search\Searchable
	 */
	public function getSearchableModel()
	{
		$componentName = $this->get('name');
		$model = DiscoveryHelper::getSearchableModel($componentName);
		return $model;
	}

	/**
	 * Populate the Solr Indexer so that batch processing uses the same object.
	 *
	 * @return  object  Hubzero\Search\Adapters\SolrIndexAdapter
	 */
	private function getSearchIndexer()
	{
		if (!isset($this->searchIndexer))
		{
			$config = Component::params('com_search');
			$this->searchIndexer = new \Hubzero\Search\Adapters\SolrIndexAdapter($config);
		}
		return $this->searchIndexer;
	}
}
