<img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
@foreach($asset->projects as $project)
<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
  <span>{{ $project->name }}</span>
</h6>
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="{{ url(route('projects.show', ['project' => $project->slug])) }}" class="nav-link">
      {{ $asset->name }}
    </a>
  </li>
</ul>
@endforeach