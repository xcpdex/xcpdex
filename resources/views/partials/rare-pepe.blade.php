<img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
  <span>Certified Rare</span>
</h6>
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="http://rarepepedirectory.com/?s={{ $asset->name }}" class="nav-link" target="_blank">
      Series {{ $asset->meta['series'] }}
    </a>
  </li>
  <li class="nav-item">
    <a href="http://rarepepedirectory.com/?s={{ $asset->name }}" class="nav-link" target="_blank">
      Card {{ $asset->meta['number'] }}
    </a>
  </li>
</ul>