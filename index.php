<!DOCTYPE html>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Tic Tac Toe (Big)</title>
        <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="jquery-ui.min.js"></script>
        <link type="text/css" rel="stylesheet" href="styles.css" />
	</head>

	<body>
		<div id="whole_container">
    		<div onClick="draw_line();" id="Title_Bar">
                Tanmay's Tic-Tac-Toe (Big)
            </div>
    <div style="overflow:hidden;"> 
        <div class="fl" id="container">
        	<canvas id="my_canvas" height="600" width="600">SomeSome</canvas>
        </div>
      
        <fieldset id="combination_selector">
            <legend>
                Select a combination
            </legend>
            There are more than 1 possible combination, select one.<br />
            <button id="next_combination" onClick="next_combination();">
                Next Combination
            </button>
            <button id="select_combination" onClick="select_combination();">
                Select This
            </button>
        </fieldset>
    
        <fieldset id="result">
        
        </fieldset>
        
        <fieldset id="status">
        
        </fieldset>
        <fieldset>
            <legend>
                About The Game
            </legend>
            Two players are needed to play the game.<br /><br />
            First player will have 'X' and second player will have 'O' as His/Her symbol.<br /><br />
            3 equal symbols are needed to be arranged consecutively in side by side or diagonal format to create successful combination.<br /><br />
            Among the 3 symbols, 2 symbols will must have to be unused. Each symbol is considered used if it is a part of another successful combination.<br /><br />
            The winner is the one with most successful combinations made by his symbols.
        </fieldset>
    </div>
    <fieldset id="addv">
    	<div class="fl">
        	Created By Tanmay Chakrabarty
        </div>
        <div class="fr">
        	<a href="http://tanmayonrun.blogspot.com">
            	More at Tanmay On Run Blog
            </a>
        </div>
    </fieldset>
</div>
<script type="text/javascript">
	var canvas;
	var drawing_board;
	canvas = document.getElementById("my_canvas");
	drawing_board = canvas.getContext("2d");
	drawing_board.fillStyle="#FF0000";
	drawing_board.strokeStyle = "#FF0000";
	
	var selecting_combinations = false;
	var combination_number = 0;
	var current = "X";
	var count_x = 0;
	var count_y = 0;
	var total_in_a_row = (600 / 50);
	var total_in_a_col = (600 / 50);
	var total_pixels = total_in_a_row * total_in_a_col;
	var this_possible_combinations = new Array();
	$("#result").html("<legend>Score Board</legend><span>X = " + count_x + "<br />O = " + count_y + "</span>");
	$("#status").html("<legend>Status Board</legend><span>Player " + current + "'s turn.</span>");
	produce_grid(total_pixels,function(){
		alert("The game has loaded.");
		});
	function highlight_item(the_item){
		var pre_color = $(the_item).css("background-color");
		
	 	$(the_item).animate({backgroundColor: '#FF0000'}, 500);
		$(the_item).animate({backgroundColor: pre_color}, 500);
		}
	function produce_grid(total_pixels, callback){
		for(i=1;i<=total_in_a_col;i++){
			for(j=1;j<=total_in_a_row;j++){
				$("#container").append("<div id='" + i + "_" + j + "' class='pixels'></div>");
				}
			}
		
		callback();
		}
	$('.pixels').click(function(){
		if(!$(this).hasClass("placed") && selecting_combinations == false){
			$(this).text(current);
			var combinations = get_all_combinations(this);
			this_possible_combinations = [];
			this_possible_combinations = get_all_possible_combinations(combinations);
			if(this_possible_combinations.length > 1){
				show_combinations(this_possible_combinations);
				increment_score();
				}
			else if(this_possible_combinations.length == 1){
				var boxes = this_possible_combinations[0].split(",");
				increment_score();
				set_selected(boxes);
				}
			else change_current_player();
			$(this).addClass("placed");
			}
		});
	function set_selected(boxes){
		var the_class = "";
		if(current == "X") the_class = "green_back";
		else the_class = "blue_back";
		$("#"+boxes[0]).addClass("crossed");
		$("#"+boxes[1]).addClass("crossed");
		$("#"+boxes[2]).addClass("crossed");
		
		$("#"+boxes[0]).removeClass("temp_crossed");
		$("#"+boxes[1]).removeClass("temp_crossed");
		$("#"+boxes[2]).removeClass("temp_crossed");
		
		$("#"+boxes[0]).removeClass(the_class);
		$("#"+boxes[1]).removeClass(the_class);
		$("#"+boxes[2]).removeClass(the_class);
		
		$("#"+boxes[0]).delay(100).addClass(the_class,500);
		$("#"+boxes[1]).delay(100).addClass(the_class,500);
		$("#"+boxes[2]).delay(100).addClass(the_class,500);
		
		draw_connector(boxes);
		}
	function draw_connector(boxes){
		var starting_pos = $("#"+boxes[0]).position();
		var ending_pos = $("#"+boxes[2]).position();
		draw_line(starting_pos.left + 25,starting_pos.top + 25,ending_pos.left + 25,ending_pos.top + 25);
		}
	function draw_line(x,y,to_x,to_y){
		drawing_board.beginPath();
		drawing_board.moveTo(x,y);
		drawing_board.lineTo(to_x,to_y);
		drawing_board.stroke();
		}
	function show_combinations(possible_combinations){
		$("#combination_selector").show();
		highlight_item($("#combination_selector"));
		$(".pixels").css("border-color","#CCC");
		combination_number = 0;
		selecting_combinations = true;
		var boxes = possible_combinations[combination_number].split(",");
		$("#"+boxes[0]).addClass("temp_crossed");
		$("#"+boxes[1]).addClass("temp_crossed");
		$("#"+boxes[2]).addClass("temp_crossed");
		}
	function next_combination(){
		combination_number++;
		if(combination_number == this_possible_combinations.length) combination_number = 0;
		
		$(".temp_crossed").removeClass("temp_crossed");
		var boxes = this_possible_combinations[combination_number].split(",");
		$("#"+boxes[0]).addClass("temp_crossed");
		$("#"+boxes[1]).addClass("temp_crossed");
		$("#"+boxes[2]).addClass("temp_crossed");
		}
	function select_combination(){
		selecting_combinations = false;
		var boxes = new Array();
		$(".temp_crossed").each(function(index, element) {
			boxes.push($(element).attr("id"));
			});
		set_selected(boxes);
		$("#combination_selector").hide();
		$(".pixels").css("border-color","#999");
		}
	function increment_score(){
		if(current == "X") count_x++;
		else count_y++;
		$("#result").html("<legend>Score Board</legend><span>X = " + count_x + "<br />O = " + count_y + "</span>");
		}
		
	function get_all_possible_combinations(combinations){
		
		var possible_combinations = new Array();
		
		$.each(combinations, function(index,value){
			
			var boxes = value.split(",");
			
			if($("#"+boxes[0]).text() == $("#"+boxes[1]).text() && $("#"+boxes[1]).text() == $("#"+boxes[2]).text()){
				var total_crossed = 0;
				if($("#"+boxes[0]).hasClass("crossed")) total_crossed++;
				if($("#"+boxes[1]).hasClass("crossed")) total_crossed++;
				if($("#"+boxes[2]).hasClass("crossed")) total_crossed++;
				if(total_crossed < 2) possible_combinations.push(value);
				}
			});
		return possible_combinations;
		}
		
	function change_current_player(){
		if(current == "X"){
			current = "O";
			$(".pixels").css("cursor","url('images/o.cur'),auto");
			}
		else{
			current = "X";
			$(".pixels").css("cursor","url('images/x.cur'),auto");
			}
		$("#status").html("<legend>Status Board</legend><span>Player " + current + "'s turn.</span>");
		}
	function get_all_combinations(this_box){
		var temp_comb = "";
		var combinations = new Array();
		var this_x = parseInt($(this_box).attr('id').split("_")[0]);
		var this_y = parseInt($(this_box).attr('id').split("_")[1]);

		//By Row
		if(this_y - 2 >= 1){
			temp_comb = this_x + "_" + (this_y - 2) + "," + this_x + "_" + (this_y - 1) + "," + this_x + "_" + (this_y);
			combinations.push(temp_comb);
			}
		if((this_y - 1 >= 1) && (this_y + 1 <= total_in_a_row)){
			temp_comb = this_x + "_" + (this_y - 1) + "," + this_x + "_" + (this_y) + "," + this_x + "_" + (this_y + 1);
			combinations.push(temp_comb);
			}
		if(this_y + 2 <= total_in_a_row){
			temp_comb = this_x + "_" + (this_y) + "," + this_x + "_" + (this_y + 1) + "," + this_x + "_" + (this_y + 2);
			combinations.push(temp_comb);
			}
		//By Col
		if(this_x - 2 >= 1){
			temp_comb = (this_x - 2) + "_" + this_y + "," + (this_x - 1) + "_" + this_y + "," + this_x + "_" + this_y;
			combinations.push(temp_comb);
			}
		if(this_x - 1 >= 1 && this_x + 1 <= total_in_a_col){
			temp_comb = (this_x - 1) + "_" + this_y + "," + (this_x) + "_" + this_y + "," + (this_x + 1) + "_" + this_y;
			combinations.push(temp_comb);
			}
		if(this_x + 2 <= total_in_a_row){
			temp_comb = (this_x) + "_" + this_y + "," + (this_x + 1) + "_" + this_y + "," + (this_x + 2) + "_" + this_y;
			combinations.push(temp_comb);
			}
		//By Dig - TOP_LEFT to Bottom_right
		if(this_x - 2 >= 1 && this_y - 2 >= 1){
			temp_comb = (this_x - 2) + "_" + (this_y - 2) + "," + (this_x - 1) + "_" + (this_y - 1) + "," + this_x + "_" + this_y;
			combinations.push(temp_comb);
			}
		if(this_x - 1 >= 1 && this_y - 1 >= 1 && this_x + 1 <= total_in_a_col && this_y + 1 <= total_in_a_row){
			temp_comb = (this_x - 1) + "_" + (this_y - 1) + "," + (this_x) + "_" + (this_y) + "," + (this_x + 1) + "_" + (this_y + 1);
			combinations.push(temp_comb);
			}
		if(this_x + 2 <= total_in_a_col && this_y + 2 <= total_in_a_row){
			temp_comb = (this_x) + "_" + (this_y) + "," + (this_x + 1) + "_" + (this_y + 1) + "," + (this_x + 2) + "_" + (this_y + 2);
			combinations.push(temp_comb);
			}
		//By Dig - TOP_RIGHT to Bottom_Left
		if(this_x - 2 >= 1 && this_y + 2 <= total_in_a_row){
			temp_comb = (this_x - 2) + "_" + (this_y + 2) + "," + (this_x - 1) + "_" + (this_y + 1) + "," + this_x + "_" + this_y;
			combinations.push(temp_comb);
			}
		if(this_x - 1 >= 1 && this_y + 1 <= total_in_a_row && this_x + 1 <= total_in_a_col && this_y - 1 >= 1){
			temp_comb = (this_x - 1) + "_" + (this_y + 1) + "," + (this_x) + "_" + (this_y) + "," + (this_x + 1) + "_" + (this_y - 1);
			combinations.push(temp_comb);
			}
		if(this_x + 2 <= total_in_a_col && this_y - 2 >= 1){
			temp_comb = (this_x) + "_" + (this_y) + "," + (this_x + 1) + "_" + (this_y - 1) + "," + (this_x + 2) + "_" + (this_y - 2);
			combinations.push(temp_comb);
			}
		return combinations;
		}
</script>
</body>
</html>
