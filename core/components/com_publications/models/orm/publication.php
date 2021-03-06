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
 * @author    Kevin Wojkovich <kevinw@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Publications\Models\Orm;

use Hubzero\Database\Relational;
use stdClass;

require_once __DIR__ . DS . 'version.php';
require_once __DIR__ . DS . 'rating.php';
require_once __DIR__ . DS . 'type.php';
require_once __DIR__ . DS . 'category.php';

/**
 * Model class for publication
 */
class Publication extends Relational implements \Hubzero\Search\Searchable
{
	/**
	 * Fields and their validation criteria
	 *
	 * @var  array
	 */
	protected $rules = array(
		'title' => 'notempty'
	);

	public $activeVersion = null;

	/**
	 * Automatic fields to populate every time a row is created
	 *
	 * @var  array
	 */
	public $initiate = array(
		'created',
		'created_by'
	);

	/**
	 * Component configuration
	 *
	 * @var  object
	 */
	protected $config = null;

	/**
	 * Establish relationship to type
	 *
	 * @return  object
	 */
	public function type()
	{
		return $this->oneToOne(__NAMESPACE__ . '\\Type', 'id', 'master_type');
	}

	/**
	 * Establish relationship to category
	 *
	 * @return  object
	 */
	public function category()
	{
		return $this->oneToOne(__NAMESPACE__ . '\\Category', 'id', 'category');
	}

	/**
	 * Establish relationship to versions
	 *
	 * @return  object
	 */
	public function versions()
	{
		return $this->oneToMany(__NAMESPACE__ . '\\Version', 'publication_id');
	}

	/**
	 * Establish relationship to ratings
	 *
	 * @return  object
	 */
	public function ratings()
	{
		return $this->oneToMany(__NAMESPACE__ . '\\Rating', 'publication_id');
	}

	/**
	 * Get the creator of this entry
	 *
	 * @return  object
	 */
	public function creator()
	{
		return $this->belongsToOne('Hubzero\User\User', 'created_by');
	}

	/**
	 * Establish relationship to group
	 *
	 * @return  object
	 */
	public function group()
	{
		return $this->belongsToOne('Hubzero\\User\\Group', 'gidNumber', 'group_owner');
	}

	/**
	 * Establish relationship to project
	 *
	 * @return  object
	 */
	public function project()
	{
		return $this->belongsToOne('Components\Projects\Models\Orm\Project', 'project_id');
	}

	/**
	 * Get the ancestor this was forked from
	 *
	 * @return  object
	 */
	public function ancestor()
	{
		return $this->belongsToOne(__NAMESPACE__ . '\\Publication', 'forked_from');
	}

	/**
	 * Delete the record and all associated data
	 *
	 * @return  boolean  False if error, True on success
	 */
	public function destroy()
	{
		// Remove ratings
		foreach ($this->ratings as $rating)
		{
			if (!$rating->destroy())
			{
				$this->addError($rating->getError());
				return false;
			}
		}

		// Remove versions
		foreach ($this->versions as $version)
		{
			if (!$version->destroy())
			{
				$this->addError($version->getError());
				return false;
			}
		}

		// Attempt to delete the record
		return parent::destroy();
	}

	/**
	 * Get a configuration value
	 * If no key is passed, it returns the configuration object
	 *
	 * @param   string  $key      Config property to retrieve
	 * @param   mixed   $default
	 * @return  mixed
	 */
	public function config($key=null, $default=null)
	{
		if (!isset($this->config))
		{
			$this->config = \Component::params('com_publications');
		}
		if ($key)
		{
			return $this->config->get($key, $default);
		}
		return $this->config;
	}

	/**
	 * Build and return the url
	 *
	 * @param   string  $as
	 * @return  string
	 */
	public function tags()
	{
		$cloud = new \Components\Tags\Models\Cloud();
		$filters = array(
			'scope' => 'publications',
			'scope_id' => $this->id
		);
		return $cloud->tags('list', $filters);
	}

	/**
	 * Get most recent version that is still marked as active
	 * 
	 * @return Components\Publications\Models\Orm\Version
	 */
	public function getActiveVersion()
	{
		if (empty($this->activeVersion))
		{
			$versions = $this->versions->sort('id', false);
			foreach ($versions as $version)
			{
				if ($version->state == 1)
				{
					$this->activeVersion = $version;
					break;
				}
			}
			if (empty($this->activeVersion))
			{
				$this->activeVersion = $versions->first();
			}
		}
		return $this->activeVersion;
	}

	/*
	 * Generate link to current active version
	 * @return string 
	 */
	public function link()
	{
		$link = 'index.php?option=com_publications';
		$link .= $this->get('alias') ? '&alias=' . $this->get('alias') : '&id=' . $this->get('id');
		$link .= $this->_base . '&v=' . $this->getActiveVersion()->version_number;
		return $link;
	}

	/*
	 * Namespace used for solr Search
	 * @return string
	 */
	public function searchNamespace()
	{
		$searchNamespace = 'publication';
		return $searchNamespace;
	}

	/*
	 * Generate solr search Id
	 * @return string
	 */
	public function searchId()
	{
		$searchId = $this->searchNamespace() . '-' . $this->id;
		return $searchId;
	}

	/*
	 * Generate search document for Solr
	 * @return array
	 */
	public function searchResult()
	{
		$activeVersion = $this->getActiveVersion();
		if (!$activeVersion)
		{
			return false;
		}

		$obj = new stdClass;
		$obj->id            = $this->searchId();
		$obj->hubtype       = $this->searchNamespace();
		$obj->title         = $activeVersion->get('title');

		$description = $activeVersion->get('abstract') . ' ' . $activeVersion->get('description');
		$description = html_entity_decode($description);
		$description = \Hubzero\Utility\Sanitize::stripAll($description);

		$obj->description   = $description;
		$obj->url = rtrim(Request::root(), '/') . Route::urlForClient('site', $this->link());
		$obj->doi           = $activeVersion->get('doi');

		$tags = $this->tags();
		if (count($tags) > 0)
		{
			$obj->tags = array();
			foreach ($tags as $tag)
			{
				$title = $tag->get('raw_tag', '');
				$description = $tag->get('tag', '');
				$label = $tag->get('label', '');
				$obj->tags[] = array(
					'id' => 'tag-' . $tag->id,
					'title' => $title,
					'description' => $description,
					'access_level' => $tag->admin == 0 ? 'public' : 'private',
					'type' => 'publication-tag',
					'badge_b' => $label == 'badge' ? true : false
				);
			}
		}

		$authors = $activeVersion->authors;
		foreach ($authors as $author)
		{
			$obj->author[] = $author->name;
		}

		$obj->owner_type = 'user';
		$obj->owner = $this->created_by;
		if ($activeVersion->statusName != 'published')
		{
			$obj->access_level = 'private';
		}
		elseif ($activeVersion->statusName == 'published')
		{
			if ($this->access == 0)
			{
				$obj->access_level = 'public';
			}
			elseif ($this->access == 1)
			{
				$obj->access_level = 'registered';
			}
			else
			{
				$obj->access_level = 'private';
			}
		}
		return $obj;
	}

	/**
	 * Get total number of records that will be indexed by Solr.
	 * @return integer
	 */
	public static function searchTotal()
	{
		$total = self::all()->total();
		return $total;
	}

	/**
	 * Get records to be included in solr index
	 * @return Hubzero\Database\Rows
	 */
	public static function searchResults($limit, $offset = 0)
	{
		return self::all()->start($offset)->limit($limit)->rows();
	}
}
