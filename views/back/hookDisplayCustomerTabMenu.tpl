{if isset($data_customer.id_customer)}
	<li class="{if $tab eq "branches"}active_menu_app{/if}">
		<a href="{$url_site}{$admin_uri}/customers/show?id_customer={$data_customer.id_customer}&tab=branches">
		   <i class="material-icons">warehouse</i>
			Sucursales
		</a>
	</li>
{/if}