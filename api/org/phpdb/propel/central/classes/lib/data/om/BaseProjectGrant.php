<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/data/ProjectGrantPeer.php';

/**
 * Base class that represents a row from the 'PROJECT_GRANT' table.
 *
 * 
 *
 * @package    lib.data.om
 */
abstract class BaseProjectGrant extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ProjectGrantPeer
	 */
	protected static $peer;


	/**
	 * The value for the projid field.
	 * @var        double
	 */
	protected $projid;


	/**
	 * The value for the fund_org field.
	 * @var        string
	 */
	protected $fund_org;


	/**
	 * The value for the award_num field.
	 * @var        string
	 */
	protected $award_num;


	/**
	 * The value for the award_url field.
	 * @var        string
	 */
	protected $award_url;


	/**
	 * The value for the nees_award_type_id field.
	 * @var        double
	 */
	protected $nees_award_type_id;

	/**
	 * @var        Project
	 */
	protected $aProject;

	/**
	 * @var        NeesAwardType
	 */
	protected $aNeesAwardType;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [projid] column value.
	 * 
	 * @return     double
	 */
	public function getProjectId()
	{

		return $this->projid;
	}

	/**
	 * Get the [fund_org] column value.
	 * 
	 * @return     string
	 */
	public function getFundingOrg()
	{

		return $this->fund_org;
	}

	/**
	 * Get the [award_num] column value.
	 * 
	 * @return     string
	 */
	public function getAwardNumber()
	{

		return $this->award_num;
	}

	/**
	 * Get the [award_url] column value.
	 * 
	 * @return     string
	 */
	public function getAwardUrl()
	{

		return $this->award_url;
	}

	/**
	 * Get the [nees_award_type_id] column value.
	 * 
	 * @return     double
	 */
	public function getNeesAwardTypeId()
	{

		return $this->nees_award_type_id;
	}

	/**
	 * Set the value of [projid] column.
	 * 
	 * @param      double $v new value
	 * @return     void
	 */
	public function setProjectId($v)
	{

		if ($this->projid !== $v) {
			$this->projid = $v;
			$this->modifiedColumns[] = ProjectGrantPeer::PROJID;
		}

		if ($this->aProject !== null && $this->aProject->getId() !== $v) {
			$this->aProject = null;
		}

	} // setProjectId()

	/**
	 * Set the value of [fund_org] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setFundingOrg($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->fund_org !== $v) {
			$this->fund_org = $v;
			$this->modifiedColumns[] = ProjectGrantPeer::FUND_ORG;
		}

	} // setFundingOrg()

	/**
	 * Set the value of [award_num] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAwardNumber($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->award_num !== $v) {
			$this->award_num = $v;
			$this->modifiedColumns[] = ProjectGrantPeer::AWARD_NUM;
		}

	} // setAwardNumber()

	/**
	 * Set the value of [award_url] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAwardUrl($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->award_url !== $v) {
			$this->award_url = $v;
			$this->modifiedColumns[] = ProjectGrantPeer::AWARD_URL;
		}

	} // setAwardUrl()

	/**
	 * Set the value of [nees_award_type_id] column.
	 * 
	 * @param      double $v new value
	 * @return     void
	 */
	public function setNeesAwardTypeId($v)
	{

		if ($this->nees_award_type_id !== $v) {
			$this->nees_award_type_id = $v;
			$this->modifiedColumns[] = ProjectGrantPeer::NEES_AWARD_TYPE_ID;
		}

		if ($this->aNeesAwardType !== null && $this->aNeesAwardType->getId() !== $v) {
			$this->aNeesAwardType = null;
		}

	} // setNeesAwardTypeId()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->projid = $rs->getFloat($startcol + 0);

			$this->fund_org = $rs->getString($startcol + 1);

			$this->award_num = $rs->getString($startcol + 2);

			$this->award_url = $rs->getString($startcol + 3);

			$this->nees_award_type_id = $rs->getFloat($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = ProjectGrantPeer::NUM_COLUMNS - ProjectGrantPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating ProjectGrant object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectGrantPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ProjectGrantPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectGrantPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aProject !== null) {
				if ($this->aProject->isModified()) {
					$affectedRows += $this->aProject->save($con);
				}
				$this->setProject($this->aProject);
			}

			if ($this->aNeesAwardType !== null) {
				if ($this->aNeesAwardType->isModified()) {
					$affectedRows += $this->aNeesAwardType->save($con);
				}
				$this->setNeesAwardType($this->aNeesAwardType);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ProjectGrantPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += ProjectGrantPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aProject !== null) {
				if (!$this->aProject->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
				}
			}

			if ($this->aNeesAwardType !== null) {
				if (!$this->aNeesAwardType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aNeesAwardType->getValidationFailures());
				}
			}


			if (($retval = ProjectGrantPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProjectGrantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getProjectId();
				break;
			case 1:
				return $this->getFundingOrg();
				break;
			case 2:
				return $this->getAwardNumber();
				break;
			case 3:
				return $this->getAwardUrl();
				break;
			case 4:
				return $this->getNeesAwardTypeId();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProjectGrantPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getProjectId(),
			$keys[1] => $this->getFundingOrg(),
			$keys[2] => $this->getAwardNumber(),
			$keys[3] => $this->getAwardUrl(),
			$keys[4] => $this->getNeesAwardTypeId(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProjectGrantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setProjectId($value);
				break;
			case 1:
				$this->setFundingOrg($value);
				break;
			case 2:
				$this->setAwardNumber($value);
				break;
			case 3:
				$this->setAwardUrl($value);
				break;
			case 4:
				$this->setNeesAwardTypeId($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProjectGrantPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setProjectId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFundingOrg($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setAwardNumber($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setAwardUrl($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setNeesAwardTypeId($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ProjectGrantPeer::DATABASE_NAME);

		if ($this->isColumnModified(ProjectGrantPeer::PROJID)) $criteria->add(ProjectGrantPeer::PROJID, $this->projid);
		if ($this->isColumnModified(ProjectGrantPeer::FUND_ORG)) $criteria->add(ProjectGrantPeer::FUND_ORG, $this->fund_org);
		if ($this->isColumnModified(ProjectGrantPeer::AWARD_NUM)) $criteria->add(ProjectGrantPeer::AWARD_NUM, $this->award_num);
		if ($this->isColumnModified(ProjectGrantPeer::AWARD_URL)) $criteria->add(ProjectGrantPeer::AWARD_URL, $this->award_url);
		if ($this->isColumnModified(ProjectGrantPeer::NEES_AWARD_TYPE_ID)) $criteria->add(ProjectGrantPeer::NEES_AWARD_TYPE_ID, $this->nees_award_type_id);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ProjectGrantPeer::DATABASE_NAME);


		return $criteria;
	}

	/**
	 * Returns NULL since this table doesn't have a primary key.
	 * This method exists only for BC and is deprecated!
	 * @return     null
	 */
	public function getPrimaryKey()
	{
		return null;
	}

	/**
	 * Dummy primary key setter.
	 *
	 * This function only exists to preserve backwards compatibility.  It is no longer
	 * needed or required by the Persistent interface.  It will be removed in next BC-breaking
	 * release of Propel.
	 *
	 * @deprecated
	 */
	 public function setPrimaryKey($pk)
	 {
		 // do nothing, because this object doesn't have any primary keys
	 }

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of ProjectGrant (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setProjectId($this->projid);

		$copyObj->setFundingOrg($this->fund_org);

		$copyObj->setAwardNumber($this->award_num);

		$copyObj->setAwardUrl($this->award_url);

		$copyObj->setNeesAwardTypeId($this->nees_award_type_id);


		$copyObj->setNew(true);

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     ProjectGrant Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     ProjectGrantPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ProjectGrantPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Project object.
	 *
	 * @param      Project $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setProject($v)
	{


		if ($v === null) {
			$this->setProjectId(NULL);
		} else {
			$this->setProjectId($v->getId());
		}


		$this->aProject = $v;
	}


	/**
	 * Get the associated Project object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Project The associated Project object.
	 * @throws     PropelException
	 */
	public function getProject($con = null)
	{
		// include the related Peer class
		include_once 'lib/data/om/BaseProjectPeer.php';

		if ($this->aProject === null && ($this->projid > 0)) {

			$this->aProject = ProjectPeer::retrieveByPK($this->projid, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = ProjectPeer::retrieveByPK($this->projid, $con);
			   $obj->addProjects($this);
			 */
		}
		return $this->aProject;
	}

	/**
	 * Declares an association between this object and a NeesAwardType object.
	 *
	 * @param      NeesAwardType $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setNeesAwardType($v)
	{


		if ($v === null) {
			$this->setNeesAwardTypeId(NULL);
		} else {
			$this->setNeesAwardTypeId($v->getId());
		}


		$this->aNeesAwardType = $v;
	}


	/**
	 * Get the associated NeesAwardType object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     NeesAwardType The associated NeesAwardType object.
	 * @throws     PropelException
	 */
	public function getNeesAwardType($con = null)
	{
		// include the related Peer class
		include_once 'lib/data/om/BaseNeesAwardTypePeer.php';

		if ($this->aNeesAwardType === null && ($this->nees_award_type_id > 0)) {

			$this->aNeesAwardType = NeesAwardTypePeer::retrieveByPK($this->nees_award_type_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = NeesAwardTypePeer::retrieveByPK($this->nees_award_type_id, $con);
			   $obj->addNeesAwardTypes($this);
			 */
		}
		return $this->aNeesAwardType;
	}

} // BaseProjectGrant
