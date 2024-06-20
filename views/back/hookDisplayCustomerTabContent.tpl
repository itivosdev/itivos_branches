{if $tab eq "branches"}
	{include file="$back_theme/_partials/renderTableBody.tpl"}
	<script type="text/javascript">
		$(document).ready(function() {
			$("#customer_footer").remove();
		});
	</script>
{/if}