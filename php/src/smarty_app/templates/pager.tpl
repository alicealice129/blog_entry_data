<nav>
<ul class="pagination">
{if $page eq 1}
<li class="page-item active"><a class="page-link" href="page.php?page=1#{$ref}">1</a></li>
{else}
<li class="page-item"><a class="page-link" href="page.php?page={$page-1}#{$ref}"><</a></li>
<li class="page-item"><a class="page-link" href="page.php?page=1#{$ref}">1</a></li>
{if $page neq $nb_page}
<li class="page-item active"><a class="page-link" href="page.php?page={$page}#{$ref}">{$page}</a></li>
{/if}
{/if}
{if $nb_page neq 1}
{if $page eq $nb_page}
<li class="page-item active"><a class="page-link" href="page.php?page={$page}#{$ref}">{$page}</a></li>
{else}
<li class="page-item"><a class="page-link" href="page.php?page={$nb_page}#{$ref}">{$nb_page}</a></li>
<li class="page-item"><a class="page-link" href="page.php?page={$page+1}#{$ref}">></a></li>
{/if}
{/if}
</ul>
</nav>