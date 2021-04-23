<form id="configuration_form" class="defaultForm form-horizontal" action="" method="post" enctype="multipart/form-data">
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			<i class="icon-share"></i>
			{l s='Activar boton de chat de sticker' d='Modules.productsticker.Shop'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='Activar sticker' d='Modules.productsticker.Shop'}
				</label>
				<div class="col-lg-9">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="sticker_status" id="sticker_status_on" value="1">
						<label for="sticker_status_on">{l s='Sí' d='Modules.productsticker.Shop'}</label>
						<input type="radio" name="sticker_status" id="sticker_status_off" value="0">
						<label for="sticker_status_off">{l s='No' d='Modules.productsticker.Shop'}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3" for="sticker_class">
					{l s='Clase del sticker' d='Modules.productsticker.Shop'}
				</label>							
				<div class="col-lg-9">
					<input type="text" name="sticker_class" id="sticker_class" class="fixed-width-lg">
					<p class="help-block">
						{l s='Establece la clase para este sticker ejemplo: mysticker' d='Modules.productsticker.Shop'}
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3" for="id_category">
					{l s='Categoria del sticker' d='Modules.productsticker.Shop'}
				</label>							
				<div class="col-lg-9">
					<select name="id_category" id="id_category"class="fixed-width-lg">
						<option value="">Selecciona una categoria</option>
						{foreach from=$categories item=categorie}
							<option value="{$categorie.id_category}">{$categorie.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='sticker' d='Modules.productsticker.Shop'}
				</label>
				<div class="col-lg-6">
					<input id="sticker_IMG" type="file" name="sticker_IMG" class="hide">
					<div class="dummyfile input-group">
						<span class="input-group-addon"><i class="icon-file"></i></span>
						<input id="sticker_IMG-name" type="text" class="disabled" name="filename" readonly="">
						<span class="input-group-btn">
							<button id="stickerIMG-selectbutton" type="button" name="submitAddAttachment" class="btn btn-default">
								<i class="icon-folder-open"></i>{l s='Selecciona un archivo' d='Modules.productsticker.Shop'}
							</button>
						</span>
					</div>
					<p class="help-block">
						{l s='Establece una imagen de 222x74 descarga esta de ejemplo ->' d='Modules.productsticker.Shop'}
						<a href="{$uri}/images/sticker.png" target="_blank">
							{l s='descargar' d='Modules.productsticker.Shop'}
						</a>
					</p>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="submit" value="1" id="sticker_env" name="sticker_env" class="btn btn-default pull-right">
				<i class="process-icon-save"></i>{l s='Guardar' d='Modules.productsticker.Shop'}
			</button>
		</div>
	</div>
</form>
<div class="panel col-xs-12 col-md-12">
	<div class="row">
		<table class="table table-striped">
			<tr>
				<th>#</th>
				<th>Sticker</th>
				<th>Clase</th>
				<th>Fecha</th>
				<th>Status</th>
				<th>Nombre categoria</th>
				<th>Eliminar</th>
			</tr>
			{foreach from=$stickers item=sticker}
				<tr class="tr_{$sticker.id_sticker}">
					<td>{$sticker.id_sticker}</td>
					<td style="width: 250px;">
						<img src="{$uri}/images/{$sticker.sticker}" class="img-thumbnail">
					</td>
					<td>{$sticker.sticker_class}</td>
					<td>{$sticker.sticker_date}</td>
					<td>{$sticker.sticker_status}</td>
					<td>{$sticker.name}</td>
					<td>
						<button class="btn btn-danger delete-sticker" data-id="{$sticker.id_sticker}" data-img="{$sticker.sticker}">
							Eliminar
						</button>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
</div>
<script>
	{literal}
	var urlBase = '{/literal}{$module_dir}{literal}';
	$(document).ready(function(){
		$('#stickerIMG-selectbutton').click(function(e){
			$('#sticker_IMG').trigger('click');
		});

		$('#sticker_IMG').change(function(e){
			var val = $(this).val();
			var file = val.split(/[\\/]/);
			$('#sticker_IMG-name').val(file[file.length-1]);
		});
	});

	$(document).on('click', '.delete-sticker', function(e) {
		e.preventDefault();
	    var id = $(this).data('id');
	    var img = $(this).data('img');
	    var url = urlBase+'productstickerajax.php';
	    var action = 'deleteSticker';
	    //var data = {id:id,action:action};
	    //var r = callService(url,data);

	    $.ajax({
			data: {id:id, img:img, action:action},
			type: "POST",
			url: url,
			beforeSend: function() {
				
			}
		}).done(function( data, textStatus, jqXHR ) {
			if (data) {
				console.log(data);
				$('.tr_'+id).remove();
			}
		});
	});

	function callService(url,data) {
		$.ajax({
			data: data,
			type: "POST",
			url: url,
			beforeSend: function() {
				
			}
		}).done(function( data, textStatus, jqXHR ) {
			if ( console && console.log ) {
				console.log( "La solicitud se ha completado ciudad." );
			}
		});
	}
{/literal}
</script>