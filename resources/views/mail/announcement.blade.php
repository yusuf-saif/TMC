<p>Assalamu alaykum,</p>
<p><strong>{{ $announcement->title }}</strong></p>
<div style="white-space:pre-wrap;line-height:1.6;color:#374151;">{{ Str::limit(strip_tags($announcement->body), 280) }}</div>
<p style="margin-top:10px;"><a href="{{ route('announcements.show',$announcement->slug) }}">Read more</a></p>
<p>— The Muhsinat Club</p>
