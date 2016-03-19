<?php
/**
 * Pico AttachURL Plugin
 * 記事にURLを添付する
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class AttachURL extends AbstractPicoPlugin {

  protected $enabled = false;
  private $attachurl_text;
  
  public function onConfigLoaded(array &$config)
  {
    $this->attachurl_text = "Attached URL";
    if(isset($config['attachurl']) && isset($config['attachurl']['defaulttext'])){
      $this->attachurl_text = $config['attachurl']['defaulttext'];
    }
  }

  public function onMetaHeaders(array &$headers)
  {
  	$headers['attachurl'] = 'URL';
  	$headers['attachurl_text'] = 'URLText';
  }
	
  public function onSinglePageLoaded(array &$pageData)
  {
    $page_meta = $pageData['meta'];
    if(!empty($page_meta['attachurl'])){
      $baseurl = substr($this->getBaseUrl(), 0, -1);
      if(is_array($page_meta['attachurl']))
      {
        $urls = $page_meta['attachurl'];
      }else{
        $urls = explode(",", $page_meta['attachurl']);        
      }
      $urltexts = explode(",", isset($page_meta['attachurl_text']) ? $page_meta['attachurl_text'] : "");
      $urllist = array();
      for($i = 0; $i < count($urls); $i++){
        $u;
        $t;
        if(is_array($urls[$i])){
          $u = $urls[$i]['url'];
          $t = $urls[$i]['text'];
        }else{
          $urs = explode("::", trim($urls[$i]), 2);
          $u = count($urs) >= 2 ? $urs[1] : $urs[0];
          $t = count($urs) >= 2 ? $urs[0] : 
            (empty($urltexts[$i]) ? $this->attachurl_text : trim($urltexts[$i])); 
        }
        $e = false;
        if(substr($u, 0, 1) == "/"){
          // 自サイト内のパス
          $u = $baseurl . $u;
        }else{
          // URL
          $e = true;
        }
        $urllist[] = array('url' => $u, 'text' => $t, 'external' => $e);
      }
      $pageData['attachurl'] = $urllist;
    }
  }
}

?>