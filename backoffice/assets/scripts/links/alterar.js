var SITE = SITE || {};
 
SITE.fileInputs = function() {
  var $this = $(this),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $this.siblings('.button'),
      $fakeFile = $this.siblings('.file-holder');
	if(newVal !== '') 
	{
    	$button.text('Arquivo selecionado');
		if($fakeFile.length === 0) 
		{
			$button.after('<span class="file-holder">' + newVal + '</span>');
		}
		else 
		{
			$fakeFile.text(newVal);
		}
	}
};

$(document).ready(function(){		

	$('input:button, input:submit').button();
	
	$('#breadCrumb3').jBreadCrumb();
	
	$('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);
	
	$("#Data_Evento").datepicker();

	$("#Fim_Evento").datepicker();
	
	$("#Data_Limite").datepicker();

    $('#Eventos').selectize({
        sortField: 'text'
    });	

	ClassicEditor
	.create( document.querySelector( '#editor' ), {
		 toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
	} )
	.then( editor => {
		window.editor = editor;
	} )
	.catch( err => {
		console.error( err.stack );
	} );	
	
});