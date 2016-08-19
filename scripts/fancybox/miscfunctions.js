// JavaScript Document
$(document).ready(function() 
{
	function confirmdelete(url)
	{
			form=document.form1;
				if(confirm("Are you sure you want to delete this?"))
				{
					if(url.indexOf('?')==-1)
					{
						url=url+"?";
					}
					form.action=url+"&action=delete";
					form.submit();
				}
	}
});
