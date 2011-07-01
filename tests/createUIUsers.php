<?php
/***********************************************************
 Copyright (C) 2011 Hewlett-Packard Development Company, L.P.

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 ***********************************************************/

/**
 * \brief Create UI users for tests to use.
 *
 * This script is part of the test infrastructure and should be run before
 * any other tests are run.
  *
 * @version "$Id$"
 *
 * Created on March 31, 2009
 */

require_once ('fossologyTestCase.php');
require_once ('TestEnvironment.php');

global $URL;

class createUIUsers extends fossologyTestCase {
  
  public $mybrowser;
  public $webProxy;
  protected $roUserFolderId;
  protected $rwUserFolderId;
  protected $uploaderFolderId;
  protected $analyzeFolderId;
  protected $downLoaderFolderId;

  function setUp() {
    
    global $URL;
    
    $this->Login();
    // create folders for the simple ui users
    $this->roUserFolderId = $this->createFolder(1, 'rouser',
      'Read only User folder');
    $this->downLoaderFolderId = $this->createFolder(1, 'downloader',
      'downloader User folder');
    $this->rwUserFolderId = $this->createFolder(1, 'rwuser',
      'Read/Write User folder');
    $this->uploaderFolderId = $this->createFolder(1, 'uploader',
      'Uploader User folder');
    $this->analyzeFolderId = $this->createFolder(1, 'anauser',
      'Analyze User folder');
  }

  function testcreateUiUsers() {
    
    global $URL;
    
    // fields are:
    // User, Desc, email, access, folder, block, blank, password, Enote, bucket
    // ui
    $simple = 'simple';
    $orig = 'original';
    $Users = array(
      'fosstester' =>
        "Primary Test User: runs test suites,fosstester,10,1,NULL,NULL,
          fosstester,y,1,$orig",
      'noemail' =>
        "test user with NO Email notification,NULL,10,1,NULL,NULL,noemail,NULL,
          1,$orig",
      'jenkins' =>
        "Jenkins user for test runs started from jenkins,NULL,10,1,NULL,NULL,
          jenkins,y,1,$orig",
      'rouser' =>
        "Read only User: simple Ui,NULL,1,$this->roUserFolderId,NULL,NULL,'',
          NULL,1,$simple",
      'downloader' =>
        "download user: simple ui,NULL,2,$this->downLoaderFolderId,NULL,NULL,'',
          NULL,1,$simple",
      'rwuser' =>
        "read/write user: simple ui,NULL,3,$this->rwUserFolderId,NULL,NULL,'',
          NULL,1,$simple",
      'uploader' =>
        "upload user: simple ui,NULL,4,$this->uploaderFolderId,NULL,NULL,'',
          NULL,1,$simple",
      'rwuser' =>
        "read/write user: simple ui,NULL,5,$this->analyzeFolderId,NULL,NULL,'',
        NULL,1,$simple",
    );

    $Svn = `svnversion`;
    $date = date('Y-m-d');
    $time = date('h:i:s-a');
    print "Starting testcreateUIUsers on: " . $date . " at " . $time . "\n";
    print "Using Svn Version:$Svn\n";
    foreach($Users as $user => $params) {
      list($description, $email, $access, $folder,
      $block, $blank, $password, $Enote, $bucket, $ui ) = split(',',$Users[$user]);
      $added = $this->addUser($user, $description, $email, $access, $folder,
        $password, $Enote, $bucket, $ui);
      if(preg_match('/User already exists/',$added, $matches)) {
        $this->pass();
        continue;
      }
      if(!empty($added)) {
        $this->fail("User $user was not added to the fossology database\n$added\n");
      }
    }
  }
}
?>
