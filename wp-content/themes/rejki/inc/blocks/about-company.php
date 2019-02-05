<div class="about_company">
	<h3><?php echo get_field('about_company_title', 'option'); ?></h3>
	<div class="col-56"><span class="simple-text"><?php echo get_field('about_company_descr', 'option'); ?> </span></div>
	<div class="col-44">
		<div class="wrr">
		<table>
			<tr><td>Услуга</td><td>Кол-во выполненных</td></tr>
			
			<?php
			$variable = get_field('about_count', 'option');
			 foreach ($variable as $key => $value): ?>
				<tr>
				<td><?php echo $value['name_ser']; ?></td>
					<td><div class="range">
					<div data-val="<?php echo $value['numb'] ?>" class="inner"><?php echo $value['numb'] ?></div>
					</div>
				</td>
			</tr>
			<?php endforeach ?>
			
				</table>
				</div>
			</div>
		</div>