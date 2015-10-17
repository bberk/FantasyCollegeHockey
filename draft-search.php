 <div class="contact-form">

<form method="get" name="nameCheckForm"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Who Would You Like to Draft?</legend>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
	Name</label></div>
				<div class="controls"><input type="text" name="nameCheck" id="nameCheck" value="Example: Johnny Gaudreau" class="required" size="30" onclick = "javascript:clearField(this);" required aria-required="true" /></div>
			</div>
		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick="javascript:showUser(document.forms['nameCheckForm'].elements['nameCheck'].value,'../../draft-results');">Go &gt;&gt;</button>	
		</div>
		</fieldset>
	</form>
</div>