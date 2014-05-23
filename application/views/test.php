
<p>
  <input type="button" id="wrap-strong" value="Wrap strong tag / 強調タグで囲む" />
  <input type="button" id="wrap-link" value="Wrap link tag / リンクタグで囲む" />
</p>

<textarea id="textarea">
Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.
Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.

It is a paradisematic country, in which roasted parts of sentences fly into your mouth.
Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.
The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.
She packed her seven versalia, put her initial into the belt and made herself on the way.

When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.
Pityful a rethoric question ran over her cheek, then 
</textarea>

<p>
  <input type="button" id="sel-textarea" value="Get selected text / 選択テキストを取得" />
</p>
<script>
// Wrap strong tag / 強調タグで囲む
$('#wrap-strong').click(function(){
  $('#textarea')
    // insert before string '<strong>'
    // <strong> を選択テキストの前に挿入
    .selection('insert', {text: '<strong>', mode: 'before'})
    // insert after string '</strong>'
    // </strong> を選択テキストの後に挿入
    .selection('insert', {text: '</strong>', mode: 'after'});
});

// Wrap link tag / リンクタグで囲む
$('#wrap-link').click(function(){
  // Get selected text / 選択テキストを取得
  var selText = $('#textarea').selection();

  $('#textarea')
    // insert before string '<a href="'
    // <a href=" を選択テキストの前に挿入
    .selection('insert', {text: '<a href="', mode: 'before'})
    // replace selected text by string 'http://'
    // 選択テキストを http:// に置き換える（http:// を選択状態に）
    .selection('replace', {text: 'http://'})
    // insert after string '">SELECTED TEXT</a>' 
    // ">選択テキスト</a> を選択テキストの後に挿入
    .selection('insert', {text: '">'+ selText + '</a>', mode: 'after'});
});

// Get selected text / 選択テキストを取得
$('#sel-textarea').click(function(){
  // alert selected text
  // テキストエリアの選択範囲をアラートする
  alert($('#textarea').selection());
  $('#textarea').focus();
});
</script>