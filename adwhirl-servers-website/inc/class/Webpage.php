<?php
require_once('inc/class/FirePHPCore/FirePHP.class.php');
 
require_once('inc/class/FirePHPCore/fb.php');

require_once 'inc/class/AWSmarty.php';

require_once 'inc/class/User.php';

abstract class Webpage {

  protected $title;
  protected $tabs_left;
  protected $tabs_right;
  protected $tab_current;
  protected $breadcrumbs;
  protected $subtitle;
  protected $needSwitcher;
  protected $switcherList;
  protected $switcherText;
  protected $styleSheets;
  protected $jsFiles;
  protected $printHeader;
  protected $printFooter;
	protected $displayFinalArrowInBreadcrumbs = false;

  /**
   * default smarty
   */
  protected $smarty;
  /**
   * header and footer smarty
   */
  protected $p_smarty;
  protected $sdb;
  protected $user = null;

  public function __construct() {
    session_start();
    $this->breadcrumbs = array();
    $this->styleSheets = array();
    $this->jsFiles = array();
    $this->smarty = new AWSmarty();
    $this->p_smarty = new AWSmarty();
    
    $this->title = 'AdWhirl';
		$this->jsFiles[] = '/js/common.js';
    $this->printHeader = true;
    $this->printFooter = true;
  }

  public function pageHeader() {
		$className = get_class($this);
    if($this->printHeader) {
			$reportUrl = '/reports/applicationReports';
			if ($className=='oneApp' && !empty($this->app)) {
				$reportUrl .= '?aid='.$this->app->id;
			} elseif ($className=='ad' && !empty($this->houseAd)) {
				$reportUrl = '/reports/houseAdReports?cid=' . $this->houseAd->id;
			}
			$user_tabs_left = array('apps' => array('name' => 'Apps', 'url' => '/apps/apps', 'display' => true),
				     'houseAds' => array('name' => 'House Ads', 'url' => '/houseAds/houseAds', 'display' => true),
				     'reports' => array('name' => 'Reports', 'url' => $reportUrl, 'display' => true));
				$non_user_tabs_left =		array('home' => array('name' => 'Home', 'url' => '/home/hom', 'display' => true));

			    $this->tabs_left = isset($_SESSION['uid'])?$user_tabs_left:$non_user_tabs_left;
			if (isset($_SESSION['uid'])) {
				$this->p_smarty->assign('user',new User($_SESSION['uid']));				
			}
			
	    $this->tabs_right = array('resources' => array('name' => 'Dev Resources', 'url' => '/home/dev', 'display' => true));
      $this->p_smarty->assign('className', $className);
      $this->p_smarty->assign('title', $this->title);
      $this->p_smarty->assign('tabs_left', $this->tabs_left);
      $this->p_smarty->assign('tabs_right', $this->tabs_right);
      $this->p_smarty->assign('tab_current', $this->tab_current);
			if ($this->requiresUser()) {
      	$this->p_smarty->assign('breadcrumbs', $this->breadcrumbs);
			}
			$this->p_smarty->assign('extra_breadcrumbs', $this->displayFinalArrowInBreadcrumbs);
      $this->p_smarty->assign('subtitle', $this->subtitle);
      $this->p_smarty->assign('needSwitcher', $this->needSwitcher);
     	$this->p_smarty->assign('switcherText', $this->switcherText);
      $this->p_smarty->assign('switcherList', $this->switcherList);
      $this->p_smarty->assign('styleSheets', $this->styleSheets);
      $this->p_smarty->assign('jsFiles', $this->jsFiles);
      
      return $this->p_smarty->fetch('../tpl/www/header.tpl');
    }
  }

  public function pageFooter() {
    if($this->printFooter) {
      return $this->p_smarty->fetch('../tpl/www/footer.tpl');
    }
  }

  public function authenticate() {
    if($this->requiresUser()) {
      $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
      $this->user = new User($uid);

      if($this->user->id != null) {
				$this->p_smarty->assign('user', $this->user);
				return true;
      }      
      return false;
    }
    return true;
  }

  public function redirect($url)  {
    $this->p_smarty->assign('url', $url);
    echo $this->p_smarty->fetch('../tpl/www/common/redirect.tpl');
    die();
  }

  abstract function __default();
  abstract function requiresUser();
}
