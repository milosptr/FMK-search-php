<?php
	 $db = new mysqli('localhost', 'alphaeff_fmk', 'J^9}qKeKTel4', 'alphaeff_fmkstage');
 ?>

 <form class="form-inline"  method="POST" action="" accept-charset="utf-8">
	<input class="form-control keywords-search-input" type="text" placeholder="Studiraj na FMK" name="keywords" values="" required>

	<select class="form-control first-search-select" name="polje1">
	  <option value="" disabled selected hidden>Departmani</option>
	  <option>Mediji i komunikacije</option>
	  <option>Psihologija</option>
	  <option>Digitalne umetnosti</option>
	  <option>Socijalni rad</option>
	  <option>Kritičke studije politike</option>
	  <option>Humanistika i teorija umetnosti </option>
	</select>

	<select class="form-control second-search-select" name="polje2">
	  <option value="" disabled selected hidden>Studijski programi</option>
	  <option>BA: Komunikacije i odnosi sa javnošću</option>
	  <option>BA: Novinarstvo i studije medija</option>
	  <option>BA: Digitalni marketing</option>
	  <option>BA: Psihologija</option>
	  <option>BA: Digitalne umetnosti</option>
	  <option>BA: Socijalni rad</option>
	  <option>BA: Studije politike</option>
	  <option>MA: Komunikacije i odnosi sa javnošću</option>
	  <option>MA: Upravljanje ljudskim resursima</option>
	  <option>MA: Kreativno izdavaštvo i žurnalizam</option>
	  <option>MA: Mediji, umetnost i nove tehnologije</option>
	  <option>MA: Primenjena psihologija</option>
	  <option>MA: Politička psihologija</option>
	  <option>MA: Digitalne umetnosti</option>
	  <option>MA: Socijalni rad</option>
	  <option>MA: Kritičke studije politike</option>
	  <option>MA: Međunarodni odnosi i diplomatija</option>
	  <option>MA: Studije transdisciplinarne humanistike i teorije umetnosti</option>
	  <option>PhD:  Transdisciplinarne studije savremene umetnosti i medija</option>
	  <option>Letnja škola komparativnih studija konflikta</option>
	  <option>Letnja škola za istraživanje seksualnosti, kulture i politike</option>
	  <option>Otvoreni kurs: Društvena teorija za kritiku kapitalizma</option>
	</select>

	<input id="search-submit-btn" type="submit" value="Traži" name="submit">
 </form>
 <div class="rezultati">
 	<div id="kurs-search">
	 	<h2 class="kursevi-search">Kursevi</h2>
	</div>
	<div id="prof-search">
		<h2 class="profesori-search">Profesori</h2> 
	</div>
 	<div id="vesti-search">
		<h2 class="vesti-search">Vesti</h2> 
	</div>
	<div id="ostalo-search">
		<h2 class="ostalo-search">Ostali članci</h2> 
	</div>

  <?php 
 	if(isset($_POST['submit'])) {
 		  $keywords = $db->escape_string($_POST['keywords']);
 		  $polje1= $db->escape_string($_POST['polje1']);
 		  $polje2 = $db->escape_string($_POST['polje2']);
		  $query = $db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
 		  $query = $db->query("
			SELECT post_title, ID, post_date
			FROM wp_posts 
			WHERE post_title LIKE '%$keywords%' AND post_title LIKE '%$polje1%' AND post_title LIKE '%$polje2%'
			OR post_content LIKE '%$keywords%' AND post_content LIKE '%$polje1%' AND post_content LIKE '%$polje2%'
			
 		  	"); 
		
    ?>
 <?php
	$brojac1 = 0; $brojac2 = 0; $brojac3 = 0; $brojac4 = 0;
	
	if($query->num_rows) {
		
		while ( $r = $query->fetch_object()) {
 			$tmpID = $r->ID;
 			$tmpUrl = get_permalink($tmpID);
			$categories = get_the_category($tmpID);
			$catsName = $categories[0]->name;
			$postdate = date('n.j.Y', strtotime($r->post_date));
			$posttitle = $r->post_title;
			
			
			// if is from kursevi
			if(strpos($tmpUrl, 'kursevi')){?>
				<div class="kursevi-result">
					<a href="<?php echo get_permalink($tmpID); ?>"><?php echo $posttitle; ?></a>
					<span class="catordate-right"><?php echo $catsName; ?></span>
				</div>
			<?php 
				} // end of if is from kursevi
				
			// if is from profesori	
			if (strpos($tmpUrl, 'profesori') !== false){ ?>
				<div class="profesori-result">
					<a href="<?php echo get_permalink($tmpID); ?>"><?php echo $posttitle; ?></a>
					<span class="catordate-right"><?php echo $catsName; ?></span>
				</div>
			<?php
			} // end of if is from profesori
			
		
			// if is from vesti
			if ($catsName == 'Blog'){?>
				<div class="vesti-result">
					<a href="<?php echo get_permalink($tmpID); ?>"><?php echo $posttitle; ?></a>
					<span class="catordate-right"><?php echo $postdate; ?></span>
				</div>
			<?php
			} // end of if is from vesti
			
			// everything else
			if (!strpos($tmpUrl, 'kursevi') && !strpos($tmpUrl, 'profesori') && $catsName != 'Blog'){?>
				<div class="ostalo-result">
					<a href="<?php echo get_permalink($tmpID); ?>"><?php echo $posttitle; ?></a>
					<span class="catordate-right"><?php echo $postdate; ?></span>
				</div>
			<?php
			}
		  } // end of while
		}// end of query->num_rows if
	} // end of isset post submit
 ?>
</div>

<script type="text/javascript">
	var kurs=0, prof=0, vesti=0, ostalo=0;
	jQuery( ".rezultati div" ).each(function() {
		if(jQuery('.rezultati div').hasClass('kursevi-result')){
			jQuery('.rezultati .kursevi-result').appendTo('#kurs-search');
			kurs++;
		}
		if(jQuery('.rezultati div').hasClass('profesori-result')){
			jQuery('.rezultati .profesori-result').appendTo('#prof-search');
			prof++;
		}
		if(jQuery('.rezultati div').hasClass('vesti-result')){
			jQuery('.rezultati .vesti-result').appendTo('#vesti-search');
			vesti++;
		}
		if(jQuery('.rezultati div').hasClass('ostalo-result')){
			jQuery('.rezultati .ostalo-result').appendTo('#ostalo-search');
			ostalo++;
		}
		
		if(kurs==0)
			jQuery("#kurs-search").remove();
		if(prof==0) 
			jQuery("#prof-search").remove();
		if(vesti==0)
			jQuery("#vesti-search").remove();
		if(ostalo==0)
			jQuery("#ostalo-search").remove();
	});
	jQuery(document).ready(function(){
    	setTimeout(function(){
    		var lista = jQuery(".second-search-select .option");
    			jQuery(lista).on('click', function() {
						jQuery(".second-search-select span").css('color', 'white');
						setTimeout(function(){
       					var str = jQuery('.second-search-select .current').text();
						var len = str.length;
       					var out = str.substring(0, 22);
						if (len>22)
        					jQuery(".second-search-select span").replaceWith('<span class="current">'+ out +'...</span>');
							jQuery(".second-search-select span").css('color', 'black');

					},5);
      			});
      	}, 3000);
    });
		
</script>