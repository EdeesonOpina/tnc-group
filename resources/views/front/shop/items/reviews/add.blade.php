@include('layouts.front.header')
		
<main>
	
		
	<div class="container margin_60_35">
		<form action="{{ route('shop.items.reviews.create', [$inventory->id]) }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="write_review">
						<h1>Write a review for {{ $inventory->item->name }}</h1>
						<div class="rating_submit">
							<div class="form-group">
							<label class="d-block">Overall rating</label>
							<span class="rating mb-0">
								<input type="radio" class="rating-input" id="5_star" name="value" value="5"><label for="5_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="4_star" name="value" value="4"><label for="4_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="3_star" name="value" value="3"><label for="3_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="2_star" name="value" value="2"><label for="2_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="1_star" name="value" value="1"><label for="1_star" class="rating-star"></label>
							</span>
							</div>
						</div>
						<!-- /rating_submit -->
						<div class="form-group">
							<label>Title of your review</label>
							<input class="form-control" type="text" name="title" placeholder="If you could say it in one sentence, what would you say?">
						</div>
						<div class="form-group">
							<label>Your review</label>
							<textarea class="form-control" name="description" style="height: 180px;" placeholder="Write your review to help others learn about this online business"></textarea>
						</div>
						<div class="form-group">
							<label>Add your photo (optional)</label>
							<div class="fileupload"><input type="file" name="image" accept="image/*"></div>
						</div>
						<button type="submit" class="btn_1">Submit review</button>
					</div>
				</div>
		</div>
		<!-- /row -->
		</form>
		</div>
		<!-- /container -->
	</main>
	<!--/main-->
	
@include('layouts.front.footer')