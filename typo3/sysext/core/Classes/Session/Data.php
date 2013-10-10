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


/**
 * Data structure for session data
 *
 * @package TYPO3\CMS\Core\Session
 */
class Data {

	/**
	 * @var array $content the payload
	 */
	protected $content = array();

	/**
	 * @var array $content additional session information
	 */
	protected $metaInfo = array();

	/**
	 * @var integer $timeout end of session lifetime (Unix epoche)
	 */
	protected $timeout = 0;

	/**
	 * @var string $identifier
	 */
	protected $identifier;

	/**
	 * @param array $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return array
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @param int $timestamp
	 */
	public function setTimeout($timestamp) {
		$this->timeout = intval($timestamp);
	}

	/**
	 * @return int
	 */
	public function getTimeout() {
		return $this->timeout;
	}

	/**
	 * @return array
	 */
	public function getMetaInfo() {
		return $this->metaInfo;
	}

	/**
	 * @param array $metaInfo
	 */
	public function setMetaInfo($metaInfo) {
		$this->metaInfo = $metaInfo;
	}

}