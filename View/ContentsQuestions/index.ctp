<?php echo $this->element('menu');?>
<?php echo $this->Html->css('contentsquestions')?>
<div class="contents-questions-index full-view">
	<div class="breadcrumb">
	<?php
	// 管理者による学習履歴表示モードの場合、コース一覧リンクを表示しない
	if($is_admin_record)
	{
		$course_url = array('controller' => 'contents', 'action' => 'record', $record['Course']['id'], $record['Record']['user_id']);
	}
	else
	{
		$course_url = array('controller' => 'contents', 'action' => 'index', $content['Course']['id']);

		$this->Html->addCrumb(__('コース一覧'), array('controller' => 'users_courses', 'action' => 'index'));
	}

	$content_url = array('controller' => 'contents_questions', 'action' => 'index', $content['Content']['id']);
	$this->Html->addCrumb($content['Course']['title'], $course_url);
	$this->Html->addCrumb(h($content['Content']['title'])); // addCrumb 内でエスケープされない為、別途エスケープ
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>

	<div id="lblStudySec" class="btn btn-info"></div>
	<?php $this->start('css-embedded'); ?>
	<style type='text/css'>
		<?php if($is_admin_record) { // 管理者による学習履歴表示モードの場合、ロゴのリンクを無効化 ?>
		.ib-navi-item
		{
			display: none;
		}

		.ib-logo a
		{
			pointer-events: none;
		}
		<?php }?>
	</style>
	<?php $this->end(); ?>

	<?php $this->start('script-embedded'); ?>
	

	<?php $this->end(); ?>

	<!-- テスト結果ヘッダ表示 -->
	<?php if($is_record){ ?>
		<?php
			$result_color  = ($record['Record']['is_passed']==1) ? 'text-primary' : 'text-danger';
			$result_label  = ($record['Record']['is_passed']==1) ? __('合格') : __('不合格');
		?>
		<table class="result-table" style = "float: right; margin-right : 15%; margin-left:100px; font-size : 15px;">
			<caption><?php echo __('テスト結果'); ?></caption>
			<tr>
				<td><?php echo __('合否'); ?></td>
				<td><div class="<?php echo $result_color; ?>"><?php echo $result_label; ?></div></td>
			</tr>
			<tr>
				<td><?php echo __('得点'); ?></td>
				<td><?php echo $record['Record']['score'].' / '.$record['Record']['full_score']; ?></td>
			</tr>
			<tr>
				<td><?php echo __('合格基準得点'); ?></td>
				<td><?php echo $record['Record']['pass_score']; ?></td>
			</tr>
		</table>
	<?php }?>

	<?php
		$question_index = 1; // 設問番号

		// 問題IDをキーに問題の成績が参照できる配列を作成
		$question_records = array();
		if($is_record)
		{
			foreach ($record['RecordsQuestion'] as $rec)
			{
				$question_records[$rec['question_id']] = $rec;
			}
		}
	?>

	<?php
		if($content['Content']['url'] || $slide_url){
			echo $this->Html->image("screen_left.png", array(
				'id'    => 'display_left',
				'class' => 'screen_status',
				'style' => 'cursor: pointer; filter: brightness(60%);',
				'width' =>'50',
				'height'=>'50',
				'alt'   =>'テキストのみ表示'
			));
			echo $this->Html->image("screen_middle.png", array(
				'id'    => 'display_middle',
				'class' => 'screen_status',
				'style' => 'cursor: pointer; filter: brightness(100%);',
				'width' =>'50',
				'height'=>'50',
				'alt'   =>'クイズ・テキストを両方表示'
			));
			echo $this->Html->image("screen_right.png", array(
				'id'    => 'display_right',
				'class' => 'screen_status',
				'style' => 'cursor: pointer; filter: brightness(60%);',
				'width' =>'50',
				'height'=>'50',
				'alt'   =>'クイズのみ表示'
			));
		}
	?>

	<?php
		if(!$is_record){
			echo $this->Html->link(
				'Python環境を開く',
				'https://repl.it/languages/python3',
				array('class' => 'btn btn-primary', 'target' => '_blank')
			);
			echo '&nbsp;';
			echo $this->Html->link(
				'Turtle環境を開く',
				'https://repl.it/languages/python_turtle',
				array('class' => 'btn btn-primary', 'target' => '_blank')
			);
		}
	?>
	<br/>

  <div class = "text-block" id="TextBlock"  height="100%" scrolling="yes" style="float : left; height: 800px; display : block;">
	<?php
	  if($slide_url){
			echo "<div style='max-height: 90vh;'>";
			echo $this->Html->image('TestImage.png', array(
				'id'  => 'presen',
				'alt' => 'スライドがここに表示されます',
				'style' => 'max-height: 80vh;'
			));
	?>
			<button id="back" class="btn btn-outline-secondary" style="margin-bottom: 10px; display: none">クリックして始める</button>
			<button id="next" class="btn btn-outline-primary" style="margin-bottom: 10px;">クリックして始める</button>
			<span style="float:right;">再生速度：<input type="number" id="playback-rate" value="1.0" min="0.0" max="10.0" step="0.01"></span>
			<br>
			<span id="text"></span><br>
			</div>
			<div class="alert alert-info">
				使い方説明：<br>
				右矢印キーを押すか，「次へ」をクリックすると，次のスライドが表示されます．<br>
				左矢印キーを押すか，「戻る」をクリックすると，一つ前のスライドが表示されます．<br>
				再生速度：0.0 ~ 10.0
			</div>
	<?php
	
		}else{
			$text_src = $this->Html->url(array(
				"controller" => "contents_questions",
				"action" => "show_text_url",
				$content_id
			), false);
			$body = '<iframe seamless id="contentFrame" height="100%" scrolling="yes" style="float : left; height: 800px; display : block; width: 100%;" src="'. h($text_src) .'"></iframe>';
			echo $body;
		}
		
	?>
	</div>

	<div class = "quiz-block" id = "quiz_block">

	<div id = "quiz" style="display:block;clear:both;">
	<?php echo $this->Form->create('ContentsQuestion'); ?>
		<?php foreach ($contentsQuestions as $contentsQuestion){ ?>
			<?php
			$question		= $contentsQuestion['ContentsQuestion'];	// 問題情報
			$title			= $question['title'];						// 問題のタイトル
			$body			= $question['body'];						// 問題文
			$question_id	= $question['id'];							// 問題ID

			//------------------------------//
			//	選択肢用の出力タグの生成	//
			//------------------------------//
			$option_tag		= '';										// 選択肢用の出力タグ
			$option_index	= 1;										// 選択肢番号
			$option_list	= explode('|', $question['options']);		// 選択肢リスト
			$correct_list	= explode(',', $question['correct']);		// 正解リスト
			$answer_list	= explode(',', @$question_records[$question_id]['answer']); // 選択した解答リスト

			foreach($option_list as $option)
			{
				// テスト結果履歴モードの場合、ラジオボタンを無効化
				$is_disabled = $is_record ? 'disabled' : '';

				// 複数選択(順不同)問題の場合
				if(count($correct_list) > 1)
				{
					$is_checked = (in_array($option_index, $answer_list)) ? " checked" : "";

					// 選択肢チェックボックス
					$id_label = 'answer-'.$option_index.'-'.$question_id;
					$option_tag .= sprintf('<input type="checkbox" value="%s" name="data[answer_%s][]" id="%s" %s %s> <label for="%s">%s</label><br>',
						$option_index, $question_id, $id_label, $is_checked, $is_disabled, $id_label, h($option));
				}
				else
				{
					$is_checked = (@$answer_list[0]==$option_index) ? 'checked' : '';
					// 選択肢ラジオボタン
					$id_label = 'answer-'.$option_index.'-'.$question_id;
					$option_tag .= sprintf('<input type="radio" value="%s" name="data[answer_%s]" id="%s" %s %s> <label for="%s"> %s</label><br>',
							$option_index, $question_id, $id_label, $is_checked, $is_disabled, $id_label, h($option));
				}


				$option_index++;
			}


			//------------------------------//
			//	正解、解説情報を出力		//
			//------------------------------//
			$explain_tag	= ''; // 解説用タグ
			$correct_tag	= ''; // 正解用タグ

			// テスト結果表示モードの場合
			if($is_record)
			{
				$result_img		= (@$question_records[$question_id]['is_correct']=='1') ? 'correct.png' : 'wrong.png';

				// 正解番号から正解ラベルへ変換
				$correct_label = ''; // 正解ラベル
				foreach($correct_list as $correct_no)
				{
					$correct_label .= ($correct_label=='') ? $option_list[$correct_no - 1] : '</br>'.$option_list[$correct_no - 1];
				}

				$correct_tag	= sprintf('<p class="correct-text border border-success shadow">正解 : </br>%s</p><p>%s</p>',
					$correct_label, $this->Html->image($result_img, array('width'=>'60','height'=>'60')));

				// 解説の設定
				if($question['explain']!='')
				{
					$explain_tag = sprintf('<div class="correct-text border border-danger shadow">%s</div>',
						$question['explain']);
				}
			}
			?>
			<div class="panel panel-info">
				<div class="panel-heading">問<?php echo $question_index;?></div>
				<div class="panel-body">
					<!--問題タイトル-->
					<h4><?php echo h($title) ?></h4>
					<div class="question-text border border-warning shadow">
						<!--問題文-->
						<?php echo $body ?>
					</div>

					<div class="radio-group">
						<!--選択肢-->
						<?php echo $option_tag; ?>
					</div>
					<!--正誤画像-->
					<?php echo $correct_tag ?>
					<!--解説文-->
					<?php echo $explain_tag ?>
					<?php //下をコメントアウトすると、正解が見えなくなる ?>
					<?php //echo $this->Form->hidden('correct_'.$question_id, array('value' => $question['correct'])); ?>
				</div>
			</div>
			<?php $question_index++;?>
		<?php } ?>

		<div class="form-inline"><!--start-->
		<?php
			echo '<input type="button" value="戻る" class="btn btn-secondary btn-lg" onclick="location.href=\''.Router::url($course_url).'\'">';
			echo '&nbsp;';
			if (!$is_record){  // 解答画面
				echo $this->Form->hidden('study_sec');
				echo '<input type="button" value="採点" class="btn btn-primary btn-lg btn-score" onclick="$(\'#confirmModal\').modal()">';
			}else{  // 結果画面
				echo '<input type="button" value="もう一回やる" class="btn btn-primary btn-lg btn-score" onclick="location.href=\''.Router::url($content_url).'\'">';
			}
		?>
		</div><!--end-->
		<?php echo $this->Form->end(); ?>
	<br>
	</div>
	</div>
</div>

<!--採点確認ダイアログ-->
<div class="modal fade" id="confirmModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">採点確認</h4>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			</div>
			<div class="modal-body">
				<p>採点してよろしいですか？</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
				<button type="button" class="btn btn-primary btn-score" onclick="sendData();">採点</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	var TIMELIMIT_SEC	= parseInt('<?php echo $content['Content']['timelimit'] ?>') * 60;	// 制限時間（単位：秒）
	var IS_RECORD		= '<?php echo $is_record ?>';										// テスト結果表示フラグ
	var element_rate = document.getElementById("playback-rate");


	$(function(){
		$(document).ready(function(){
			var quiz = document.getElementById('quiz_block');
			var text = document.getElementById('TextBlock');
			const non_text_url = ('<?php echo h($content['Content']['url']) ?>' || '<?php echo h($slide_url) ?>') ? false : true;
			if(non_text_url){
				text.style.display = "none";
				quiz.style.display = "block";
				quiz.style.width = "95%";
			}else{
				text.style.display = "block";
				text.style.width = "60%";
				quiz.style.display = "block";
				quiz.style.width = "35%";
			}
		});

		$('.screen_status').click(function(e){
			var quiz = document.getElementById('quiz_block');
			// var text = document.getElementById('contentFrame');
			var text = document.getElementById('TextBlock');
			var flag = document.getElementById('quiz');
			var id   = $(this).attr("id");
			if(id == 'display_left'){
				flag.className = "none";
				text.style.display = "block";
				text.style.width = "95%";
				quiz.style.display = "none";
				$("#display_left").attr("style",   "cursor: pointer; filter: brightness(100%);");
				$("#display_middle").attr("style", "cursor: pointer; filter: brightness(60%);");
				$("#display_right").attr("style",  "cursor: pointer; filter: brightness(60%);");
			}else if(id == 'display_middle'){
				flag.className = "parallel";
				text.style.display = "block";
				text.style.width = "60%";
				quiz.style.display = "block";
				quiz.style.width = "35%";
				$("#display_left").attr("style",   "cursor: pointer; filter: brightness(60%);");
				$("#display_middle").attr("style", "cursor: pointer; filter: brightness(100%);");
				$("#display_right").attr("style",  "cursor: pointer; filter: brightness(60%);");
			}else{
				flag.className = "full";
				text.style.display = "none";
				quiz.style.display = "block";
				quiz.style.width = "95%";
				$("#display_left").attr("style",   "cursor: pointer; filter: brightness(60%);");
				$("#display_middle").attr("style", "cursor: pointer; filter: brightness(60%);");
				$("#display_right").attr("style",  "cursor: pointer; filter: brightness(100%);");
			}
		});
	});

	</script>
	<?php echo $this->Html->script('contents_questions.js?20190401');?>
	<?php if($slide_url){ ?>

	<script type="text/javascript">
	// スライド読み上げ設定
	var page, sentence, stopped, date, SLIDE, SRC, voice, textLines, textData, showText, i, wait; 
	
	page = 1;
	sentence = 0;
	stopped = true;

	date = new Date();
	SLIDE = '<?php echo $slide_name; ?>';
	SRC = '<?php echo $this->webroot.'slide/'?>' + SLIDE + '/';
	voice = new Audio();
	$.ajax(
		SRC + SLIDE + '-scenario.txt', 'post'
	).done(function (beforeData) { 

		textData = beforeData.split('\n');

		textLines = beforeData.split('\n');
		textData = Array();

		textLines.forEach(function (element) {
			textData.push(
				// element.split(/(?<=。|．|\.|？|\?)/)
				element.split(/(。|．|\.(?![0-9])|？|\?|、$)/)
				.filter(function(e){return e !== "";})
				.map(function(e){return e.replace('?', '？')})
			)
		});

		textDataTmp = new Array(textData.length);
		for(let i = 0; i < textData.length; i++){
			let size;
			if(textData[i].length == 1){
				size = textData[i].length;
			}else{
				size = textData[i].length / 2;
			}
			textDataTmp[i] = new Array(Math.floor(size)).fill(" ");
		}

		for(i = 0; i < textData.length; i++){
			k = 0;
			for(j = 0; j< textData[i].length; j += 2){
				if(textData[i].length == 1){
					textDataTmp[i][k] = textData[i][j]
				}else{
					textDataTmp[i][k] = textData[i][j] + textData[i][j+1];
				}
				k++;
			}
		}

		textData = textDataTmp;
	})
	showText = function () {
		wait = 150;
		if ('，．, .'.indexOf(textData[page - 1][sentence - 1][i]) != -1) {
			wait = 600
		}
		$('span#text')[0].innerText += textData[page - 1][sentence - 1][i]; i++;
		if (i >= textData[page - 1][sentence - 1].length) {
			$('button#next')[0].innerText = '次へ'
			stopped = true;
		} else {
			setTimeout(showText, wait)
		}
	}

	let showWords = function(input){
		$('span#text')[0].innerText += input;
	}

	let showTextBeta = function(){
		return new Promise( (res,rej) => {
			for( let i = 0; i < textData[page - 1][sentence - 1].length; i++){
				if ('，．, .'.indexOf(textData[page - 1][sentence - 1][i]) != -1) {
					wait = 600;
				}else{
					wait = 150;
				}
				// setTimeout( showWords(textData[page - 1][sentence - 1][i]), wait);
				// (function(pram){
				// 	setTimeout( showWords(textData[page - 1][sentence - 1][pram]), wait);
				// });
				// console.log($('span#text')[0].innerText);
				$('span#text')[0].innerText += textData[page - 1][sentence - 1][i];
			}
			$('button#next')[0].innerText = '次へ';
			$('button#back')[0].innerText = '戻る';
			$('button#back').css('display','');
			stopped = true;
			res();
		});

	}
	

	function playSlideAndText() {
		sentence++;
		if (!stopped) { 
			console.log('No!') 
		} else {
			if (textData[page - 1][sentence - 1] == undefined) { page++; sentence = 1 }
			if (textData[page - 1] == undefined || textData[page - 1].length == 0) { page = 1; sentence = 1 }
			$('button#next')[0].innerText = '...'
			// stopped = false
			if(sentence == 1){ $('img#presen')[0].src = SRC + ('000' + page).slice(-3) + '.jpeg'; }
			voice.src = '<?php echo $this->webroot ?>' + '/contents_questions/play_sound/' + textData[page - 1][sentence - 1]
			voice.load(); 
			voice.playbackRate = element_rate.value;
			voice.play();
			$('span#text')[0].innerText = '';
			i = 0;
			// showText();
			showTextBeta().then(() => {
				// console.log(sentence);
			});
		}
	}

	function slideBackAndText(){
		sentence--;
		if (!stopped) { 
			console.log('No!') 
		} else {
			if(page == 1 && sentence == 0){
				// 暫定処理
				// page = sentence = 1;
				page = textData.length;
				sentence = textData[page - 1].length;
			}

			if (sentence == 0){
				page--;
				sentence = textData[page-1].length;
			}

			

			$('button#next')[0].innerText = '...'
			// stopped = false
			if(sentence == textData[page-1].length){ $('img#presen')[0].src = SRC + ('000' + page).slice(-3) + '.jpeg'; }
			voice.src = '<?php echo $this->webroot ?>' + '/contents_questions/play_sound/' + textData[page - 1][sentence - 1];
			voice.load(); 
			voice.playbackRate = element_rate.value;
			voice.play();
			$('span#text')[0].innerText = '';
			i = 0;
			// showText();
			showTextBeta().then(() => {
				// sentence--;
				// console.log(sentence);
			});
		}
	}

	window.onload = function () {
		$('button#next')[0].onclick = playSlideAndText;
		$('button#back')[0].onclick = slideBackAndText;
	}
	</script>
	<?php } ?>
	<script>
	document.addEventListener('keydown',(event)=>{
		var keyName = event.key;
		if(keyName == 'ArrowRight'){
			playSlideAndText();
		}else if(keyName == 'ArrowLeft'){
			slideBackAndText();
		}
	});

	document.getElementById('ContentsQuestionIndexForm').addEventListener('keydown',(e)=>{
		e.preventDefault();
	})


	element_rate.addEventListener('input', (e)=>{
		voice.playbackRate = element_rate.value;
	})
</script>