<? include $_SERVER['DOCUMENT_ROOT']."/Madmin/inc/top.php"; ?>
<?
if(empty($mode)) $mode = "insert";

if($mode == "update"){
	$sql = "select * from df_banner_main where idx=$idx";
	$prd_row = $db->row($sql);
}
?>
			<script language="JavaScript" type="text/javascript">
			<!--
				//해당 이미지를 삭제한다.
				function deleteImage(prdcode, prdimg, imgpath){
					if(imgpath == ""){
						alert("삭제할 이미지가 없습니다.");
						return;
					}else{
					if(confirm("이미지를 삭제하시겠습니까?"))
						document.location = "prd_save.php?mode=delete_image&prdcode="+prdcode+"&prdimg="+prdimg+"&imgpath="+imgpath;  
					}
					return;
				}
			//-->
			</script>

			<div class="pageWrap">
				<div class="page-heading">
					<h3>
						메인 슬라이드
					</h3>
					<ul class="breadcrumb">
						<li>팝업 설정</li>
						<li class="active">메인 슬라이드</li>
					</ul>
				</div>

				<form name="frm" action="banner_main_save.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="mode" value="<?=$mode?>">
					<input type="hidden" name="idx" value="<?=$idx?>">
					<div class="box comMTop20" style="width:978px;">
						<div class="panel">
							<table class="table orderInfo" cellpadding="0" cellspacing="0">
								<col width="15%"/><col width="35%"/><col width="15%"/><col width="35%"/>
								<tr>
									<th>제목</th>
									<td class="comALeft" colspan="3"><input type="text" name="subject" value="<?=$prd_row['subject']?>" class="form-control" style="width:94%;"> </td>
								</tr>
								<tr>
									<th>PC 배경</th>
									<td class="comALeft" colspan="3">
										<?if(is_file("../../userfiles/banner/".$prd_row['upfile_pc01'])){?>
										<a href="/userfiles/banner/<?=$prd_row['upfile_pc01']?>" target="_blank"><img src="/userfiles/banner/<?=$prd_row['upfile_pc01']?>" width="200" align="absmiddle" /></a>
										<?}?>
										<input type="file" name="upfile_pc01" class="form-control" style="width:50%;"> (1680 x 1080 px)
									</td>
								</tr>
								<tr>
									<th>PC 이미지</th>
									<td class="comALeft" colspan="3">
										<?if(is_file("../../userfiles/banner/".$prd_row['upfile_pc02'])){?>
										<a href="/userfiles/banner/<?=$prd_row['upfile_pc02']?>" target="_blank"><img src="/userfiles/banner/<?=$prd_row['upfile_pc02']?>" width="200" align="absmiddle" /></a>
										<?}?>
										<input type="file" name="upfile_pc02" class="form-control" style="width:50%;"> (1500 x 138 px)
									</td>
								</tr>
								<tr>
									<th>MOBILE 배경</th>
									<td class="comALeft" colspan="3">
										<?if(is_file("../../userfiles/banner/".$prd_row['upfile_mo01'])){?>
										<a href="/userfiles/banner/<?=$prd_row['upfile_mo01']?>" target="_blank"><img src="/userfiles/banner/<?=$prd_row['upfile_mo01']?>" width="200" align="absmiddle" /></a>
										<?}?>
										<input type="file" name="upfile_mo01" class="form-control" style="width:50%;"> (960 x 1440 px)
									</td>
								</tr>
								<tr>
									<th>MOBILE 이미지</th>
									<td class="comALeft" colspan="3">
										<?if(is_file("../../userfiles/banner/".$prd_row['upfile_mo02'])){?>
										<a href="/userfiles/banner/<?=$prd_row['upfile_mo02']?>" target="_blank"><img src="/userfiles/banner/<?=$prd_row['upfile_mo02']?>" width="200" align="absmiddle" /></a>
										<?}?>
										<input type="file" name="upfile_mo02" class="form-control" style="width:50%;"> (880 x 228 px)
									</td>
								</tr>
								<tr>
									<th>링크 URL</th>
									<td class="comALeft" colspan="3">
										<input type="text" name="url" value="<?=$prd_row['url']?>" class="form-control" style="width:80%;"> 
										<label style="margin-left:15px;">
											<input type="checkbox" name="url_link" value="N" <?if($prd_row['url_link']=="N"){?>checked<?}?>> 새창으로
										</label>
									</td>
								</tr>
								<tr>
									<th>노출</th>
									<td class="comALeft" colspan="3">
										<label style="margin-right:15px;">
											<input type="radio" name="showset" value="Y" <? if($prd_row['showset'] == "Y" || empty($prd_row['showset'])) echo "checked"; ?>> 노출함
										</label>
										<label style="margin-right:15px;">
											<input type="radio" name="showset" value="N" <? if($prd_row['showset'] == "N") echo "checked"; ?>> 노출안함
										</label>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="box comMTop20 comMBottom20" style="width:978px;">
						<div class="comPTop20 comPBottom20">
							<div class="comFLeft comACenter" style="width:10%;">
								<button class="btn btn-primary btn-sm" type="button" onClick="location.href='banner_main.php?page=<?=$page?>';">목록</button>
							</div>
							<div class="comFLeft comACenter" style="width:90%;">
								<button class="btn btn-info btn-sm" type="submit">확인</button>
								<button class="btn btn-default btn-sm" type="button" onClick="location.href='banner_main.php?page=<?=$page?>';">취소</button>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					
				</form>
					
			</div>
		</div>
	</div>
</div>

</body>
</html>
