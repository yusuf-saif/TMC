<p>Assalamu alaykum,</p>
<p>Here is today's reflection from The Muhsinat Club.</p>
<p><strong>Type:</strong> {{ ucfirst($reflection->type) }}</p>
<p><strong>Title:</strong> {{ $reflection->title }}</p>
<div style="white-space:pre-wrap;line-height:1.6;color:#374151;">{{ $reflection->body }}</div>
@if($reflection->source)
  <p style="margin-top:10px;color:#6b7280;">Source: {{ $reflection->source }}</p>
@endif
<p style="margin-top:16px;">Jazakillahu khayran,</p>
<p>The Muhsinat Club</p>
