<?php
	 $db = new mysqli('localhost', 'alphaeff_fmk', 'J^9}qKeKTel4', 'alphaeff_fmkstage');
 ?>

<div id='kursevisearch' class="container-search">
	<div class="header-course">
		<h2>
			Katalog kurseva
		</h2>
	</div>    
	
	<form class="form-inline" action="" method="post" accept-charset="utf-8">
	   <select class="form-control" name="departman" placeholder="Departman">
		  <option value="" disabled selected hidden>Departman</option>
		   <option>Mediji i komunikacije</option>
		   <option>Psihologija</option>
		   <option>Digitalne umetnosti</option>
		   <option>Socijalni rad</option>
		   <option>Kritičke studije politike</option>
		   <option>THTUM</option>
		</select>
		
		<select class="form-control" name="oblast" placeholder="Oblast">
		  <option value="" disabled selected hidden>Oblast</option>
		  <option>Istorija medija i propagande</option>
		  <option>Autorski intervju</option>
		</select>
		<div class="search-text">
			<input class="form-control" type="text" name="keywords_course" required>
			<button type="submit" name="submit"><i class="fa fa-search"></i></button>
		</div>
	</form>
	
	<?php 
 		if(isset($_POST['submit'])) {
			 $selectOne = $db->escape_string($_POST['departman']);
 		     $selectSecond = $db->escape_string($_POST['oblast']);
 		     $searchField = $db->escape_string($_POST['keywords_course']);
			$query = $db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
			 $query = $db->query("
			    SELECT post_title, ID, post_date, post_excerpt, post_content
				FROM wp_posts
				LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
				LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
				WHERE (wp_term_taxonomy.term_id IN (75) AND post_title LIKE '%$selectOne%' OR  wp_term_taxonomy.term_id IN (75) AND post_title LIKE '%$searchField%' 
				OR wp_term_taxonomy.term_id IN (75) AND post_title LIKE '%$selectSecond%' OR wp_term_taxonomy.term_id IN (75) AND post_content LIKE '%$selectOne%'
				OR wp_term_taxonomy.term_id IN (75) AND post_content LIKE '%$searchField%' OR wp_term_taxonomy.term_id IN (75) AND post_content LIKE '%$selectSecond%')
				GROUP BY wp_posts.ID
			"); 
		}
	
	?>
	
	
	
	<div class="result-query">
		<span class="find-course">
			<?php if($query->num_rows == 1):?>
				Pronađen <?php echo $query->num_rows; ?> kurs
			<?php elseif($query->num_rows > 1):?>
				Pronađeno  <?php echo $query->num_rows; ?> kurseva
			<?php else: ?>
			<?php endif;?>
		</span>
	
		
		
	
		<?php 
			if($query->num_rows) {
			while ( $r = $query->fetch_object()) {

				$tmpID = $r->ID;
				$tmpExcerpt = $r->post_excerpt;
				$tmpContent = $r->post_content;
				$tmpUrl = get_permalink($tmpID);
				$categories = get_the_category($tmpID);
				$catsName = $categories[0]->name;

				?>

		         <ul class="cat-list">			
					<li>
						<p class="naslov-kursa">
							<?php echo $r->post_title;?><span><?php echo $catsName;?></span>
						</p>
						<p class="opis-kursa">
							<?php echo $tmpExcerpt; ?>
						</p>
					   <a class="more-info" href="<?php echo get_permalink($tmpID); ?>">Više informacija</a>
	                </li>
		         </ul>
				<?php 

				}
			}	
		?>	
		
	</div>
	
	
</div>