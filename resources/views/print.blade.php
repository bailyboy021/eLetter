<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<title>{{ $dataLetter->title }}</title>
		<link rel="icon" type="image/png" href="{{ asset('images/logo-bailyboy.png') }}" />
		<style>
			@page :first{
				margin-bottom: 25mm;
				margin-top: 50mm; 
			}
			@page {
				margin-top: 25mm;
				margin-left: 25mm;
				margin-right: 25mm;
				margin-bottom: 25mm;
				padding: 0;				
			}
			body {				
				margin: 0;
				padding: 0;
				font-size: 12pt;
			}
			@font-face {
				font-family: 'Roboto', sans-serif;
			}
			.page[size="A4"]{
				width: 21cm;
				height: 29.5cm;
			}

			/** Define the header rules **/
			header {
				position: fixed;
				top: -50mm;
				left: -25mm;
				right: 0;				
			}

			/** Define the footer rules **/
			footer {
				position: fixed; 
				bottom: -25mm; 
				left: -25mm; 
				right: 0;
				z-index: -99999;
			}			
			.qrcodes {
				position: absolute;
				bottom: 5mm;
				right: -15mm;
			}
			.box_ttd{
				width:100%;
				height: 60mm;
				padding: 0;
			}
			p {
				text-align: justify;
			}
		</style>
	</head>
	<body style="line-height: 100%;">		
		<script type="text/php">
			// setup
			$GLOBALS['start_pages'] = array( );
			$GLOBALS['current_start_page'] = null;
			$GLOBALS['show_page_numbers'] = false;
		</script>

		<script type="text/php">
			// section start
			$GLOBALS['current_start_page'] = $pdf->get_page_number();
			$GLOBALS['start_pages'][$pdf->get_page_number()] = array(
				'show_page_numbers' => true,
				'page_count' => 1
			);
		</script>
		<?php
			$file = $encrypt."_".$dataLetter->slug;
		?>
		<header>		
		
		</header>
		<footer>
			<img src="{{ public_path('images/qrcodes/' . $file . '.svg') }}" style="position:relative;width:20mm;height:auto;margin-left:165mm;bottom:-110mm;">
			<img src="{{ public_path('images/kop-bawah-new.png') }}" style="width:210mm;">
		</footer>
				
		<main style="line-height: 1.5;">
			<?php
				$file = $encrypt."_".$dataLetter->slug;
				use App\Helpers\Tanggal_indonesia;
				$tanggal = new Tanggal_indonesia;
				$tanggalSurat = $tanggal->tgl_indo($dataLetter->letter_date);
			?>	

			<table border="0" style="width:100%">
				<tr style="border:0">
					<td style="width:15%;padding:0;border:0;vertical-align:top">
						Nomor
					</td>
					<td style="width:2%;padding:0;border:0;vertical-align:top">
						:
					</td>
					<td style="width:33%;padding:0;border:0;vertical-align:top">
						<b><?=$dataLetter->letter_number?></b>
					</td>
					<td style="width:50%;padding:0;border:0;text-align:right">
						<span style="text-align: right">Jakarta, <?=$tanggalSurat?></span>
					</td>
				</tr>
				<tr style="border:0">
					<td style="width:15%;padding:0;border:0;vertical-align:top">
						Lampiran
					</td>
					<td style="width:2%;padding:0;border:0;vertical-align:top">
						:
					</td>
					<td style="width:83%;padding:0;border:0;vertical-align:top">
						<?= $dataLetter->attachments == 0 ? '-' : $dataLetter->attachments . ' lembar' ?>
					</td>
				</tr>
				
				<tr style="border:0">
					<td style="width:15%;padding:0;border:0;vertical-align:top">
						Perihal
					</td>
					<td style="width:2%;padding:0;border:0;vertical-align:top">
						:
					</td>
					<td style="width:83%;padding:0;border:0;vertical-align:top">
						<?=$dataLetter->title?>
					</td>
				</tr>
			</table>
			<br>
			
			<p style="padding-top:0;">
				Kepada Yang Terhormat,<br>
				<b><?=$dataLetter->recipient_name?></b><br>
				<?=$dataLetter->recipient_address?><br>
			</p>
			
			
			<p style="padding-top:0;">
				<span style="letter-spacing:1px">
				<?=$dataLetter->content?>	
				</span>
			</p>
			<br>
			
			<div style="page-break-inside: avoid;">
				<div class="box_ttd">
					<p style="margin:0">
						Hormat Kami,<br><br>
						<b><?=$dataLetter->sender_name?></b>
					</p>
				</div>
			</div>
		</main>
		<script type="text/php">
			// record total number of pages for the section
			$GLOBALS['start_pages'][$GLOBALS['current_start_page']]['page_count'] = $pdf->get_page_number() - $GLOBALS['current_start_page'] + 1;
		</script>
		
		<script type="text/php">
			$pdf->page_script('
			  if ($pdf) {
				// if (array_key_exists($PAGE_NUM, $GLOBALS["start_pages"])) {
				  // $GLOBALS["current_start_page"] = $PAGE_NUM;
				  // $GLOBALS["show_page_numbers"] = $GLOBALS["start_pages"][$GLOBALS["current_start_page"]]["show_page_numbers"];
				// }
				// if ($GLOBALS["show_page_numbers"]) {
				  // $font = $fontMetrics->get_font("verdana", "normal");
				  // $pdf->text(472, 765, "Hal " . ($PAGE_NUM - $GLOBALS["current_start_page"] + 1) . " dari " . $GLOBALS["start_pages"][$GLOBALS["current_start_page"]]["page_count"], $font, 9);
				
				// }
				
				if($PAGE_NUM - $GLOBALS["current_start_page"] + 1==1){	
					$image = public_path(). "/images/kop-atas-new.png";
					$pdf->image($image, 0, 0, 595, 120);
				}
			  }
				
			');
		</script>
	</body>
</html>