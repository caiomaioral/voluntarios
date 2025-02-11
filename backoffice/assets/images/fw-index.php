<style>

/* BOTÃO DE UPLOAD */
div.container{
	position: relative;
	width: 70px;
	height: 60px;
	/*border: 0px #999 solid;*/
}
#file{
	float: left; 
	z-index: 100; 
	cursor: hand;
	height: 70px;	
	font-size: 30px;
	width: 270px;
	filter: alpha(opacity=0);
	opacity: 0;	
	clip: rect(auto, auto, auto, 67px);
	position: absolute; 
	left: -65px; 
	top: 0px;
}
#uploadbutton {
	width:22px;
	height:22px;
	display:block;
	background-image:url(images/photobook_22x22.png);
	background-position: top left;
	border: 1px transparent;
}

</style>

<form id="FormX" name="FormX" action="" method="post">

<td id="RetornoFace" width="70" align="center">
    
    <div class="container">
        <img src="upload/fw-thumb.php?img=Imagem.jpg" width="60" height="50" class="pic_left" />
        <input id="file" name="Filedata" type="file" hidefocus="true" size="1" accept="image/jpeg" onchange="efetuaUpload();" />
    </div>
    
</td>

</form>