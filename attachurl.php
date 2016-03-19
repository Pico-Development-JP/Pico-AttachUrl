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

  private $base_url;
  
  private $content_dir;
  
  private $attachurl_text;
  
  public function onConfigLoaded(array &$config)
  {
    $this->base_url = $config['base_url'];
    $this->content_dir = $config['content_dir'];
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
        $urs = explode("::", trim($urls[$i]), 2);
        $u = count($urs) >= 2 ? $urs[1] : $urs[0];
        $t = count($urs) >= 2 ? $urs[0] : 
          (empty($urltexts[$i]) ? $this->attachurl_text : trim($urltexts[$i]));
        $e = false;
        if(substr($u, 0, 1) == "/"){
          // 自サイト内のパス
          $u = $this->base_url . $u;
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