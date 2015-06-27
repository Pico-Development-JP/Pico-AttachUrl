# pico-attachurl
Pico AttachURL Plugin:記事にURLを添付するプラグイン

## テンプレートに追加する値
 * URL: 添付するURL。,区切りで複数個指定が可能で、前後にある空白は無視する。
 * URLText: 添付するURLのリンクテキスト。,区切りで複数個指定が可能で、前後にある空白は無視する。
 
##  追加するTwig変数
それぞれのページに追加されます。({{page.attachurl}}などで呼び出し)
 * attachurl:ページに添付されたURLの情報が格納された配列。
  * url:添付されたURL。/から始まっている場合、ベースURLからの絶対パスとみなす。
  * text:添付されたURLのリンクテキスト。
  * external:URLが外部リンクであるかどうかを示すbool値。

##  コンフィグオプション
 * $config['attachurl']['defaulttext']:リンクテキストのデフォルト値。何も入力しなかった場合は、「Attached URL」となる。
