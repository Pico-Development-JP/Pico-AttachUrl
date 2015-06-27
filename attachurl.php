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
class AttachURL{

  private $base_url;
  
  private $content_dir;
  
  private $attachurl_text;
  
  public function config_loaded(&$settings) {
    $this->base_url = $settings['base_url'];
    $this->content_dir = $settings['content_dir'];
    $this->attachurl_text = "Attached URL";
    if(isset($settings['attachurl']) && isset($settings['attachurl']['defaulttext'])){
      $this->attachurl_text = $settings['attachurl']['defaulttext'];
    }
  }

  public function before_read_file_meta(&$headers)
  {
  	$headers['attachurl'] = 'URL';
  	$headers['attachurl_text'] = 'URLText';
  }
	
	public function get_page_data(&$data, $page_meta)
	{
    if(!empty($page_meta['attachurl'])){
      $urls = explode(",", $page_meta['attachurl']);
      $urltexts = explode(",", isset($page_meta['attachurl_text']) ? $page_meta['attachurl_text'] : "");
      $urllist = array();
      for($i = 0; $i < count($urls); $i++){
        $u = trim($urls[$i]);
        $t = empty($urltexts[$i]) ? $this->attachurl_text : trim($urltexts[$i]);
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
      $data['attachurl'] = $urllist;
    }
	}
}

?>