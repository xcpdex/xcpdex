<div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel" aria-hidden="true">
  <div class="modal-dialog{{ 'age-of-chains' == $asset->meta['template'] ? ' modal-lg' : '' }}" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img src="{{ $asset->image_url }}" width="100%" height="auto" />
      </div>
    </div>
  </div>
</div>