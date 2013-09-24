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


class BackendStorage extends ClassicStorage {

	/**
	 * @var string $subtype the service subtype
	 */
	protected $subtype = 'backend';

	/**
	 * Checks DB connection
	 * @return bool|void
	 */
	public function init() {
		if ($this->subtype !== $this->info['requestedServiceSubType']) {
			return FALSE;
		}
		/** @see \TYPO3\CMS\Core\Authentication\BackendUserAuthentication **/
		$this->session_table = 'be_sessions';
		$this->user_table = 'be_users';
		$this->username_column = 'username';
		$this->name = \TYPO3\CMS\Core\Authentication\BackendUserAuthentication::getCookieName();
		$this->userid_column = 'uid';
		$this->lastLogin_column = 'lastlogin';

		return parent::init();
	}
}