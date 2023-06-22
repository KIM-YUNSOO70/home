<?php
	$psize = 10;
	$perPage = 100;
	
	if($totalCnt > $perPage) {
		$pageTotal = ceil($totalCnt / (double)$perPage);
		if($cpage > $pageTotal) $cpage = $pageTotal;
		
		$pagingStart = 1;
		$pagingEnd = $pageTotal;
		$pagingLastGroupStart = (int)(($cpage - 1) / $psize) * $psize + 1;
		
		$pagingLastGroupEnd = $pagingLastGroupStart + ($psize - 1);
		
		if($pagingLastGroupEnd > $pageTotal) $pagingLastGroupEnd = $pageTotal;
		if(($pagingLastGroupStart/$psize) >= $pagingStart) {
?><a href="javascript:goPageMove(<?=$pagingStart?>);" title="Start">&lt;&lt; </a><?php
	}
	
		if($cpage - $psize > 0) {
?><a href="javascript:goPageMove(<?=$pagingLastGroupStart - 1?>);" title="Prev">&lt; </a><?php
	}
	
		for($i=$pagingLastGroupStart-1; $i<$pagingLastGroupEnd; $i++) {
			if($i == ($cpage-1)) {
?>[<b><?=($i+1)?></b>] <?php
			} else {
?><a href="javascript:goPageMove(<?=($i+1)?>);">[<?=($i+1)?>] </a><?php
			}
		}
	
		if(($pagingEnd - $pagingLastGroupEnd) > 0) {
?><a href="javascript:goPageMove(<?=($pagingLastGroupEnd+1)?>)" title="Next"> &gt;</a>
<a href="javascript:goPageMove(<?=$pagingEnd?>)" title="Last"> &gt;&gt;</a><?php
		}
    }
?>