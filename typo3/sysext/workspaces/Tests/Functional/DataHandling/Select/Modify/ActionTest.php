<?php
namespace TYPO3\CMS\Workspaces\Tests\Functional\DataHandling\Select\Modify;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Oliver Hader <oliver.hader@typo3.org>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once dirname(dirname(__FILE__)) . '/AbstractActionTestCase.php';

/**
 * Functional test for the DataHandler
 */
class ActionTest extends \TYPO3\CMS\Workspaces\Tests\Functional\DataHandling\Select\AbstractActionTestCase {

	/**
	 * @var string
	 */
	protected $assertionDataSetDirectory = 'typo3/sysext/workspaces/Tests/Functional/DataHandling/Select/Modify/DataSet/';

	/**
	 * Relations
	 */

	/**
	 * @test
	 * @see DataSet/addElementRelation.csv
	 */
	public function addElementRelation() {
		parent::addElementRelation();
		$this->assertAssertionDataSet('addElementRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1', 'Element #2', 'Element #3')
		);
	}

	/**
	 * @test
	 * @see DataSet/deleteElementRelation.csv
	 */
	public function deleteElementRelation() {
		parent::deleteElementRelation();
		$this->assertAssertionDataSet('deleteElementRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1')
		);
		$this->assertResponseContentStructureDoesNotHaveRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #2', 'Element #3')
		);
	}

	/**
	 * @test
	 * @see DataSet/changeElementSorting.csv
	 */
	public function changeElementSorting() {
		$this->markTestSkipped('Core bug, see http://forge.typo3.org/issues/56782');
		parent::changeElementSorting();
		$this->assertAssertionDataSet('changeElementSorting');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1', 'Element #2')
		);
	}

	/**
	 * @test
	 * @see DataSet/changeElementRelationSorting.csv
	 */
	public function changeElementRelationSorting() {
		parent::changeElementRelationSorting();
		$this->assertAssertionDataSet('changeElementRelationSorting');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1', 'Element #2')
		);
	}

	/**
	 * @test
	 * @see DataSet/createContentNAddRelation.csv
	 */
	public function createContentAndAddElementRelation() {
		parent::createContentAndAddElementRelation();
		$this->assertAssertionDataSet('createContentNAddRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentHasRecords($responseContent, self::TABLE_Content, 'header', 'Testing #1');
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . $this->recordIds['newContentId'], self::FIELD_ContentElement,
			self::TABLE_Element, 'title', 'Element #1'
		);
	}

	/**
	 * @test
	 * @see DataSet/createContentNCreateRelation.csv
	 */
	public function createContentAndCreateElementRelation() {
		parent::createContentAndCreateElementRelation();
		$this->assertAssertionDataSet('createContentNCreateRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentHasRecords($responseContent, self::TABLE_Content, 'header', 'Testing #1');
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . $this->recordIds['newContentId'], self::FIELD_ContentElement,
			self::TABLE_Element, 'title', 'Testing #1'
		);
	}

	/**
	 * @test
	 * @see DataSet/modifyElementOfRelation.csv
	 */
	public function modifyElementOfRelation() {
		parent::modifyElementOfRelation();
		$this->assertAssertionDataSet('modifyElementOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Testing #1', 'Element #2')
		);
	}

	/**
	 * @test
	 * @see DataSet/modifyContentOfRelation.csv
	 */
	public function modifyContentOfRelation() {
		parent::modifyContentOfRelation();
		$this->assertAssertionDataSet('modifyContentOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentHasRecords($responseContent, self::TABLE_Content, 'header', 'Testing #1');
	}

	/**
	 * @test
	 * @see DataSet/modifyBothSidesOfRelation.csv
	 */
	public function modifyBothSidesOfRelation() {
		parent::modifyBothSidesOfRelation();
		$this->assertAssertionDataSet('modifyBothSidesOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Testing #1', 'Element #2')
		);
		$this->assertResponseContentHasRecords($responseContent, self::TABLE_Content, 'header', 'Testing #1');
	}

	/**
	 * @test
	 * @see DataSet/deleteContentOfRelation.csv
	 */
	public function deleteContentOfRelation() {
		parent::deleteContentOfRelation();
		$this->assertAssertionDataSet('deleteContentOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentDoesNotHaveRecords($responseContent, self::TABLE_Content, 'header', 'Testing #1');
	}

	/**
	 * @test
	 * @see DataSet/deleteElementOfRelation.csv
	 */
	public function deleteElementOfRelation() {
		parent::deleteElementOfRelation();
		$this->assertAssertionDataSet('deleteElementOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureDoesNotHaveRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1')
		);
	}

	/**
	 * @test
	 * @see DataSet/copyContentOfRelation.csv
	 */
	public function copyContentOfRelation() {
		parent::copyContentOfRelation();
		$this->assertAssertionDataSet('copyContentOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		// Referenced elements are not copied with the "parent", which is expected and correct
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . $this->recordIds['copiedContentId'], self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #2', 'Element #3')
		);
	}

	/**
	 * @test
	 * @see DataSet/copyElementOfRelation.csv
	 */
	public function copyElementOfRelation() {
		parent::copyElementOfRelation();
		$this->assertAssertionDataSet('copyElementOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1')
		);
		// Referenced elements are not updated at the "parent", which is expected and correct
		$this->assertResponseContentStructureDoesNotHaveRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #1 (copy 1)')
		);
	}

	/**
	 * @test
	 * @see DataSet/localizeContentOfRelation.csv
	 */
	public function localizeContentOfRelation() {
		parent::localizeContentOfRelation();
		$this->assertAssertionDataSet('localizeContentOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, self::VALUE_LanguageId, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdLast, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #2', 'Element #3')
		);
	}

	/**
	 * @test
	 * @see DataSet/localizeElementOfRelation.csv
	 */
	public function localizeElementOfRelation() {
		parent::localizeElementOfRelation();
		$this->assertAssertionDataSet('localizeElementOfRelation');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageId, self::VALUE_LanguageId, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdFirst, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('[Translate to Dansk:] Element #1', 'Element #2')
		);
	}

	/**
	 * @test
	 * @see DataSet/moveContentOfRelationToDifferentPage.csv
	 */
	public function moveContentOfRelationToDifferentPage() {
		parent::moveContentOfRelationToDifferentPage();
		$this->assertAssertionDataSet('moveContentOfRelationToDifferentPage');

		$responseContent = $this->getFrontendResponse(self::VALUE_PageIdTarget, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseContent();
		$this->assertResponseContentStructureHasRecords(
			$responseContent, self::TABLE_Content . ':' . self::VALUE_ContentIdLast, self::FIELD_ContentElement,
			self::TABLE_Element, 'title', array('Element #2', 'Element #3')
		);
	}

}