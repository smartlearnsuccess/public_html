<!-- ======= Hero Section ======= -->
<style>
	.package-item-home:hover {
		transform: scale(1.025);
		box-shadow: rgba(0, 0, 0, 0.24) 0px 5px 10px
	}
</style>

<section id="hero" class="d-flex align-items-center">

	<div class="container" data-aos="zoom-out" data-aos-delay="100">
		<div class="row">
			<div class="col-xl-6">
				<?php echo __($Homesection[0]['Homesection']['sections_content']); ?>
			</div>
			<div class="col-md-5 d-flex align-items-center">
				<div class="girl-slide-img aos" data-aos="fade-up">
					<img src="<?php echo $this->webroot; ?>img/tab/<?php echo $Homesection[0]['Homesection']['sections_img'] ?>"
						alt="">
				</div>
			</div>
		</div>
	</div>

</section><!-- End Hero -->

<main id="main">

	<!-- ======= Counts Section ======= -->
	<section id="counts" class="counts">
		<div class="container" data-aos="fade-up">

			<div class="row">

				<div class="col-lg-3 col-md-6">
					<div class="count-box">
						<i class="bi bi-emoji-smile"></i>
						<span data-purecounter-start="0" data-purecounter-end="<?php echo $students; ?>"
							data-purecounter-duration="1" class="purecounter"></span>
						<p>Student count</p>
					</div>
				</div>

				<div class="col-lg-3 col-md-6 mt-5 mt-md-0">
					<div class="count-box">
						<i class="bi bi-journal-richtext"></i>
						<span data-purecounter-start="0" data-purecounter-end="<?php echo $exams; ?>"
							data-purecounter-duration="1" class="purecounter"></span>
						<p>Exam count</p>
					</div>
				</div>

				<div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
					<div class="count-box">
						<i class="bi bi-headset"></i>
						<span data-purecounter-start="0" data-purecounter-end="<?php echo $package_count; ?>"
							data-purecounter-duration="1" class="purecounter"></span>
						<p>Package count</p>
					</div>
				</div>

				<div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
					<div class="count-box">
						<i class="bi bi-people"></i>
						<span data-purecounter-start="0" data-purecounter-end="<?php echo $countExamOrder; ?>"
							data-purecounter-duration="1" class="purecounter"></span>
						<p>News count</p>
					</div>
				</div>

			</div>

		</div>
	</section><!-- End Counts Section -->

	<section id="pricing" class="pricing section-bg">
		<div class="container" data-aos="fade-up">

			<div class="section-title">
				<h2>Top Exam Services</h2>

			</div>

			<div class="row">

				<?php if (count($exam_lists) > 0): ?>

					<?php foreach ($exam_lists as $key => $examsss): ?>
						<div class="col-lg-4 col-md-6 mt-4">
							<div class="box d-flex flex-column overflow-hidden" data-aos="fade-up" data-aos-delay="100">
								<div class="flex-grow-0 flex-shrink-0">
									<h3><?php echo $exam_lists[$key]["Exam"]["name"]; ?></h3>
								</div>
								<div class="flex-grow-1 flex-shrink-1 h-100 overflow-auto scroll-class">
									<h4><?php echo $exam_lists[$key]["Exam"]["instruction"]; ?></h4>
									<h3 class="title instructor-text">
										<?php echo $exam_lists[$key]["Exam"]["instruction"]; ?>
									</h3>
								</div>
								<div class="flex-grow-0 flex-shrink-0">
									<div class="btn-wrap">
										<a href="#" class="btn-buy">Duration:
											<?php echo $exam_lists[$key]["Exam"]["duration"]; ?>
											Min.</a>
									</div>
								</div>
							</div>
						</div>

					<?php endforeach; ?>
				<?php else: ?>
					<?php echo "No records found"; ?>
				<?php endif; ?>
			</div>

		</div>
	</section><!-- End Pricing Section -->


	<section id="testimonials" class="testimonials">
		<div class="container" data-aos="fade-up">
			<div class="section-title">
				<h2><?php echo __($Homesection[1]['Homesection']['sections_heading']); ?></h2>
				<p><?php echo __($Homesection[1]['Homesection']['sections_content']) ?></p>
			</div>

			<div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
				<div class="swiper-wrapper">
					<?php if (count($package_lists) > 0): ?>

						<?php foreach ($package_lists as $key => $packages):
							$id = $package_lists[$key]["Package"]["id"];
							if (strlen($package_lists[$key]["Package"]["photo"]) > 0) {
								$photo = "img/package/" . $package_lists[$key]["Package"]["photo"];
							} else {
								$photo = "img/nia.png";
							}
							?>

							<div class="swiper-slide">
								<div class="testimonial-wrap">
									<a class="color-dark "
										href="
								<?php echo $this->Html->url(array('controller' => 'Packages', 'action' => 'singleproduct', $id, Inflector::slug(strtolower($package_lists[$key]["Package"]["name"]), "-"))) ?>">
										<div class="presnto-testimonial-item package-item-home">

											<img src="
									<?php echo $photo; ?>" class="testimonial-img" alt="">


											<h3>
												<?php echo $package_lists[$key]["Package"]["name"]; ?>
											</h3>
											<?php if ($package_lists[$key]["Package"]["package_type"] == "P"): ?>

												<h4>$
													<?php echo $package_lists[$key]["Package"]["show_amount"]; ?>
												</h4>

											<?php else: ?>
												<h4>Free</h4>

											<?php endif; ?>
											<div style="min-height: 160px; height: 160px; overflow: hidden; width: 100%;">
												<p>
													<i class="bx bxs-quote-alt-left quote-icon-left"></i>
													<?php echo $package_lists[$key]["Package"]["description"]; ?>
													<i class="bx bxs-quote-alt-right quote-icon-right"></i>
												</p>
											</div>
										</div>
									</a>
								</div>
							</div><!-- End testimonial item -->

						<?php endforeach; ?>

					<?php else: ?>
						<?php echo "No records found"; ?>

					<?php endif; ?>

				</div>
				<div class="swiper-pagination"></div>
			</div>

		</div>
	</section>



	<section class="section master-skill">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-md-12">
					<div class="section-header aos" data-aos="fade-up">
						<div class="section-sub-head">

							<h2>
								<?php echo __($Homesection[2]['Homesection']['sections_heading']); ?>
							</h2>
						</div>
					</div>
					<div class="section-text aos" data-aos="fade-up">
						<p>
							<?php echo __($Homesection[2]['Homesection']['sections_content']) ?>
						</p>
					</div>
					<?php echo __($Homesection[3]['Homesection']['sections_content']) ?>
				</div>
				<div class="col-lg-5 col-md-12 d-flex align-items-end">
					<div class="career-img aos" data-aos="fade-up">
						<img src="app/webroot/img/v2/join.png" alt="" class="img-fluid">
					</div>
				</div>
			</div>
		</div>
	</section>








</main><!-- End #main -->