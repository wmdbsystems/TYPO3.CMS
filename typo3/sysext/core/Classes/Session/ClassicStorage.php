<?php
namespace TYPO3\CMS\Core\Session;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thorsten Kahler <thorsten.kahler@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\Utility\GeneralUtility;


abstract class ClassicStorage extends \TYPO3\CMS\Core\Service\AbstractService implements StorageInterface {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	/**
	 * @var string $subtype the service subtype
	 */
	protected $subtype;

	/**
	 * @var \TYPO3\CMS\Core\Authentication\AbstractUserAuthentication
	 */
	protected $authentication;

	/**
	 * @var string $session_table DB table for session data
	 */
	protected $session_table;

	/**
	 * @var string $name cookie name
	 */
	protected $name = '';

	/**
	 * @var array $dbFields list of DB fields
	 */
	protected $dbFields = array();

	/**
	 * @var string $contentField DB field for session content
	 */
	protected $contentField = 'ses_data';

	/**
	 * @var string $identifierField DB field for session identifier (= cookie value)
	 */
	protected $identifierField = 'ses_id';

	/**
	 * @var string $timestampField
	 */
	protected $timestampField = 'ses_tstamp';

	/**
	 * Checks subtype specific attributes and DB connection and metadata
	 *
	 * @return boolean
	 */
	public function init() {
		if (!$this->session_table) {
			return FALSE;
		}
		if ($this->subtype !== $this->info['requestedServiceSubType']) {
			return FALSE;
		}
		$this->db = $GLOBALS['TYPO3_DB'];
		$this->initializeDbFields();

		return $this->dbFields && parent::init();
	}


	/**
	 * Fetch session data
	 *
	 * This method (or its delegates) has to check the timeout value!
	 *
	 * @param string $identifier the session ID
	 * @return Data session data object
	 */
	public function get($identifier) {
// TODO tk 2013-09-06 refactor DB handling (no prepared statement required here)
		$statement = $this->db->prepare_SELECTquery('*', $this->session_table, 'ses_id = :ses_id');
		$statement->execute(array(':ses_id' => $identifier));
		$row = $statement->fetch(\TYPO3\CMS\Core\Database\PreparedStatement::FETCH_ASSOC);
		$statement->free();
		$sessionData = NULL;
		if (is_array($row) && $row[$this->identifierField] === $identifier) {
			$sessionData = $this->createDataObject($row);
		}
		return $sessionData;
	}

	/**
	 * Store session data
	 *
	 * @param Data $sessionData
	 * @return boolean TRUE on success
	 */
	public function put(Data $sessionData) {
		$insertFields = $this->extractDatabaseValues($sessionData);
		$id = $sessionData->getIdentifier();
		if ($this->get($id) instanceof Data) {
			unset(
				$insertFields['ses_id'],
				$insertFields['ses_name'],
				$insertFields['ses_iplock'],
				$insertFields['ses_hashlock'],
				$insertFields['ses_userid']
			);
			$this->db->exec_UPDATEquery(
				$this->session_table,
				'ses_id = ' . $this->db->fullQuoteStr($id, $this->session_table),
				$insertFields
			);
		} else {
			$this->db->exec_INSERTquery($this->session_table, $insertFields);
		}
	}

	/**
	 * Delete session data
	 *
	 * @param string $identifier the session ID
	 * @return boolean TRUE on success
	 */
	public function delete($identifier) {
		$result = $this->db->exec_DELETEquery(
			$this->session_table,
			'ses_id = ' . $this->db->fullQuoteStr($identifier, $this->session_table)
			. ' AND ses_name = ' . $this->db->fullQuoteStr($this->name, $this->session_table)
		);
		return (boolean) $result;
	}

	/**
	 * Garbage collection removes outdated session data
	 *
	 * @return void
	 */
	public function collectGarbage() {
		$this->db->exec_DELETEquery(
			$this->session_table,
			'ses_tstamp < ' . intval(($GLOBALS['EXEC_TIME'] - $this->authentication->gc_time))
				. ' AND ses_name = ' . $this->db->fullQuoteStr($this->name, $this->session_table)
		);
	}


	/**
	 * Helper methods
	 */

	/**
	 * Transform session data object to associative array
	 *
	 * @param \TYPO3\CMS\Core\Session\Data $sessionData the session data
	 * @return array
	 */
	protected function extractDatabaseValues(Data $sessionData) {
		$content = $sessionData->getContent();
		$result = array();
		foreach($this->dbFields as $field) {
			if (isset($content[$field])) {
				$result[$field] = $content[$field];
			}
		}
		$result['ses_id'] = $sessionData->getIdentifier();
		$result['ses_name'] = $this->name;
		return $result;
	}

	/**
	 * Transform associative array to session data object
	 *
	 * @param array $values session data as associative array
	 * @return \TYPO3\CMS\Core\Session\Data
	 */
	protected function createDataObject($values) {
		/** @var \TYPO3\CMS\Core\Session\Data $sessionData */
		$sessionData = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Session\\Data');
		$sessionData->setIdentifier($values['ses_id']);
		$sessionData->setContent($values);
// TODO tk 2013-09-06 check necessity and validity of setting timeout here
//		$sessionData->setTimeout((int)$values['ses_tstamp'] + (int)$this->authentication->auth_timeout_field);
		return $sessionData;
	}

	/**
	 * Fetches field list from current session table
	 *
	 * @return void
	 */
	protected function initializeDbFields() {
// TODO tk 2013-09-06 cache field list?
		$this->dbFields = array_keys($this->db->admin_get_fields($this->session_table));
	}
}