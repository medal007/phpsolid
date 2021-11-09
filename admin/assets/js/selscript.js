$.get('https://raw.githubusercontent.com/FortAwesome/Font-Awesome/fa-4/src/icons.yml', function(data) {
  var parsedYaml = jsyaml.load(data);
	$.each(parsedYaml.icons, function(index, icon){
    $('#select').append('<option value="fa-' + icon.id + '">' + icon.id + '</option>');
  });
  
  $("#select").chosen({
    enable_split_word_search: true,
		search_contains: true 
  });
	$("#icon").html('<i class="fa fa-2x ' + $('#select').val() + '"></i>');
});

/* Detect any change of option*/
$("#select").change(function(){
  var icono = $(this).val();
	$("#icon").html('<i class="fa fa-2x ' + icono + '"></i>');
});



$.get('https://raw.githubusercontent.com/FortAwesome/Font-Awesome/fa-4/src/icons.yml', function(data) {
  var parsedYaml = jsyaml.load(data);
	$.each(parsedYaml.icons, function(index, icon){
    $('#selectic').append('<option value="fa-' + icon.id + '">' + icon.id + '</option>');
  });
  
  $("#selectic").chosen({
    enable_split_word_search: true,
		search_contains: true 
  });
	$("#iconic").html('<i class="fa fa-2x ' + $('#selectic').val() + '"></i>');
});

/* Detect any change of option*/
$("#selectic").change(function(){
  var icono = $(this).val();
	$("#iconic").html('<i class="fa fa-2x ' + icono + '"></i>');
});