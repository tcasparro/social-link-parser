<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<style>
	#atc_bar{width:500px;}
	#attach_content{border:1px solid #ccc;padding:10px;margin-top:10px;}
	#atc_images {width:100px;height:120px;overflow:hidden;float:left;}
	#atc_info {width:350px;float:left;height:100px;text-align:left; padding:10px;}
	#atc_title {font-size:14px;display:block;}
	#atc_url {font-size:10px;display:block;}
	#atc_desc {font-size:12px;}
	#atc_total_image_nav{float:left;padding-left:20px}
	#atc_total_images_info{float:left;padding:4px 10px;font-size:12px;}
</style>
<br /><br /><br /><br />

<div align="center">
	<h1>Parse a Link Like Facebook with PHP and Jquery</h1>
	<div id="atc_bar" align="center">
		Paste Link Here: <input type="text" name="url" size="40" id="url" value="http://www.louisgarneau.com/us-en/product/310735/1493805/Bags_%26amp%3B_Belts/TRI_WHEEL_T-60_BAG" />
		<input type="button" name="attach" value="Parse" id="attach" />
		<input type="hidden" name="cur_image" id="cur_image" />
		<div id="loader">
	
			<div align="center" id="atc_loading" style="display:none"><img src="load.gif" alt="Loading" /></div>
			<div id="attach_content" style="display:none">
				<div id="atc_images"></div>
				<div id="atc_info">
				 
					<label id="atc_title"></label>
					<label id="atc_url"></label>
					<br clear="all" />
					<label id="atc_desc"></label>
					<br clear="all" />
				</div>
				<div id="atc_total_image_nav" >
					<a href="#" id="prev"><img src="prev.png"  alt="Prev" border="0" /></a><a href="#" id="next"><img src="next.png" alt="Next" border="0" /></a>
				</div>
			 
				<div id="atc_total_images_info" >
					Showing <span id="cur_image_num">1</span> of <span id="atc_total_images">1</span> images
				</div>
				<br clear="all" />
			</div>
		</div>
		<br clear="all" />
	</div>
</div>


<script>

	$(document).ready(function(){	
 
		// delete event
		$('#attach').bind("click", parse_link);
		
		function parse_link ()
		{
			if(!isValidURL($('#url').val()))
			{
				alert('Please enter a valid url.');
				return false;
			}
			else
			{
				$('#atc_loading').show();
				$('#atc_url').html($('#url').val());
				$.post("fetch.php?url="+escape($('#url').val()), {}, function(response){
					
					//Set Content
					$('#atc_title').html(response.title);
					$('#atc_desc').html(response.description);
					$('#atc_total_images').html(response.total_images);
					
					$('#atc_images').html(' ');
					$.each(response.images, function (a, b)
					{
						$('#atc_images').append('<img src="'+b.img+'" width="100" id="'+(a+1)+'">');
					});
					$('#atc_images img').hide();
					
					//Flip Viewable Content 
					$('#attach_content').fadeIn('slow');
					$('#atc_loading').hide();
					
					//Show first image
					$('img#1').fadeIn();
					$('#cur_image').val(1);
					$('#cur_image_num').html(1);
					
					// next image
					$('#next').unbind('click');
					$('#next').bind("click", function(){
					 
						var total_images = parseInt($('#atc_total_images').html());			 
						if (total_images > 0)
						{
							var index = $('#cur_image').val();
							$('img#'+index).hide();
							if(index < total_images)
							{
								new_index = parseInt(index)+parseInt(1);
							}
							else
							{
								new_index = 1;
							}
							
							$('#cur_image').val(new_index);
							$('#cur_image_num').html(new_index);
							$('img#'+new_index).show();
						}
					});	
					
					// prev image
					$('#prev').unbind('click');
					$('#prev').bind("click", function(){
					 
						var total_images = parseInt($('#atc_total_images').html());				 
						if (total_images > 0)
						{
							var index = $('#cur_image').val();
							$('img#'+index).hide();
							if(index > 1)
							{
								new_index = parseInt(index)-parseInt(1);;
							}
							else
							{
								new_index = total_images;
							}
							
							$('#cur_image').val(new_index);
							$('#cur_image_num').html(new_index);
							$('img#'+new_index).show();
					 	}
					});	
				});
			}
		};	
	});

	function isValidURL(url)
	{
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		
		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}
</script>