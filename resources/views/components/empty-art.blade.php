@props(['variant' => 'chest'])

{{-- Ilustrasi pixel mini untuk empty state. Murni div + palet earth, tanpa gambar. --}}
<div class="empty-art" aria-hidden="true">
    @switch($variant)
        @case('scroll')
            {{-- papan quest + tanda seru --}}
            <span class="absolute" style="left:50%; bottom:0; width:6px; height:24px; margin-left:-3px; background:#5C4632;"></span>
            <span class="absolute" style="left:10px; top:2px; width:56px; height:28px; background:#D4A373; border:2px solid #5C4632; box-shadow:2px 2px 0 rgba(92,70,50,.25);"></span>
            <span class="absolute" style="left:22px; top:9px; width:4px; height:9px; background:#9A3F56;"></span>
            <span class="absolute" style="left:22px; top:21px; width:4px; height:4px; background:#9A3F56;"></span>
            <span class="absolute" style="left:32px; top:10px; width:24px; height:3px; background:#A6774F;"></span>
            <span class="absolute" style="left:32px; top:17px; width:18px; height:3px; background:#A6774F;"></span>
            @break

        @case('sprout')
            {{-- kecambah di gundukan tanah --}}
            <span class="absolute" style="left:50%; bottom:0; width:40px; height:10px; margin-left:-20px; background:#83644A;"></span>
            <span class="absolute" style="left:50%; bottom:9px; width:28px; height:4px; margin-left:-14px; background:#6E5138;"></span>
            <span class="absolute" style="left:50%; bottom:12px; width:4px; height:16px; margin-left:-2px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:50%; bottom:24px; width:12px; height:8px; margin-left:-15px; background:#6BA368;"></span>
            <span class="absolute" style="left:50%; bottom:26px; width:12px; height:8px; margin-left:3px; background:#6BA368;"></span>
            <span class="absolute" style="left:50%; bottom:34px; width:8px; height:8px; margin-left:-4px; background:#8FBC8A;"></span>
            @break

        @case('chest')
            {{-- peti harta terbuka & kosong --}}
            <span class="absolute" style="left:50%; top:0; width:48px; height:14px; margin-left:-24px; background:#D4A373; border:2px solid #5C4632;"></span>
            <span class="absolute" style="left:50%; top:16px; width:44px; height:6px; margin-left:-22px; background:#3E2F22;"></span>
            <span class="absolute" style="left:50%; top:22px; width:48px; height:22px; margin-left:-24px; background:#A6774F; border:2px solid #5C4632;"></span>
            <span class="absolute" style="left:50%; top:26px; width:8px; height:10px; margin-left:-4px; background:#E5B567; border:2px solid #5C4632;"></span>
            @break

        @case('shield')
            {{-- perisai dengan hati HP --}}
            <span class="absolute" style="left:50%; top:2px; width:36px; height:24px; margin-left:-18px; background:#4E7D4C; border:2px solid #3A5D39;"></span>
            <span class="absolute" style="left:50%; top:26px; width:28px; height:8px; margin-left:-14px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:50%; top:34px; width:20px; height:6px; margin-left:-10px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:50%; top:40px; width:10px; height:5px; margin-left:-5px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:50%; top:8px; width:6px; height:6px; margin-left:-9px; background:#BE546E;"></span>
            <span class="absolute" style="left:50%; top:8px; width:6px; height:6px; margin-left:3px; background:#BE546E;"></span>
            <span class="absolute" style="left:50%; top:13px; width:14px; height:7px; margin-left:-7px; background:#BE546E;"></span>
            <span class="absolute" style="left:50%; top:20px; width:8px; height:4px; margin-left:-4px; background:#BE546E;"></span>
            @break

        @case('calendar')
            {{-- kalender dengan satu tanggal aktif --}}
            <span class="absolute" style="left:50%; top:6px; width:44px; height:38px; margin-left:-22px; background:#FBF7EC; border:2px solid #5C4632;"></span>
            <span class="absolute" style="left:50%; top:6px; width:44px; height:10px; margin-left:-22px; background:#BE546E; border:2px solid #5C4632;"></span>
            <span class="absolute" style="left:50%; top:0; width:4px; height:10px; margin-left:-12px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:0; width:4px; height:10px; margin-left:8px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:22px; width:6px; height:6px; margin-left:-16px; background:#E8DEC4;"></span>
            <span class="absolute" style="left:50%; top:22px; width:6px; height:6px; margin-left:-3px; background:#E8DEC4;"></span>
            <span class="absolute" style="left:50%; top:22px; width:6px; height:6px; margin-left:10px; background:#E8DEC4;"></span>
            <span class="absolute" style="left:50%; top:32px; width:6px; height:6px; margin-left:-16px; background:#E8DEC4;"></span>
            <span class="absolute" style="left:50%; top:32px; width:6px; height:6px; margin-left:-3px; background:#6BA368;"></span>
            <span class="absolute" style="left:50%; top:32px; width:6px; height:6px; margin-left:10px; background:#E8DEC4;"></span>
            @break

        @case('piggy')
            {{-- celengan menunggu koin pertama --}}
            <span class="absolute" style="left:50%; top:0; width:10px; height:10px; margin-left:-5px; background:#F1CC8E; border:2px solid #C99845;"></span>
            <span class="absolute" style="left:50%; top:14px; width:40px; height:22px; margin-left:-20px; background:#D4869A; box-shadow: inset 0 4px 0 #E3A7B5;"></span>
            <span class="absolute" style="left:50%; top:12px; width:10px; height:3px; margin-left:-5px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:10px; width:5px; height:5px; margin-left:-16px; background:#D4869A;"></span>
            <span class="absolute" style="left:50%; top:20px; width:8px; height:8px; margin-left:16px; background:#BE546E;"></span>
            <span class="absolute" style="left:50%; top:18px; width:3px; height:3px; margin-left:8px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:36px; width:5px; height:6px; margin-left:-14px; background:#BE546E;"></span>
            <span class="absolute" style="left:50%; top:36px; width:5px; height:6px; margin-left:9px; background:#BE546E;"></span>
            @break

        @case('shelf')
            {{-- rak dengan satu buku kesepian --}}
            <span class="absolute" style="left:50%; top:0; width:52px; height:46px; margin-left:-26px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:4px; width:44px; height:38px; margin-left:-22px; background:#8A6236;"></span>
            <span class="absolute" style="left:50%; top:22px; width:44px; height:4px; margin-left:-22px; background:#5C4632;"></span>
            <span class="absolute" style="left:50%; top:8px; width:8px; height:14px; margin-left:-18px; background:#77AADD;"></span>
            <span class="absolute anim-shimmer" style="left:50%; top:30px; width:4px; height:4px; margin-left:6px; background:#E8DEC4;"></span>
            @break

        @case('chart')
            {{-- sumbu chart dengan garis datar --}}
            <span class="absolute" style="left:14px; top:4px; width:4px; height:40px; background:#5C4632;"></span>
            <span class="absolute" style="left:14px; top:40px; width:48px; height:4px; background:#5C4632;"></span>
            <span class="absolute" style="left:22px; top:32px; width:36px; height:4px; background:#A9A39E;"></span>
            <span class="absolute" style="left:36px; top:26px; width:8px; height:8px; background:#FBF7EC; border:2px solid #BE546E;"></span>
            @break

        @case('magnifier')
            {{-- kaca pembesar, gagang berundak --}}
            <span class="absolute" style="left:16px; top:4px; width:28px; height:28px; background:#5C4632;"></span>
            <span class="absolute" style="left:20px; top:8px; width:20px; height:20px; background:#F5EFE0;"></span>
            <span class="absolute" style="left:24px; top:12px; width:6px; height:6px; background:#FBF7EC;"></span>
            <span class="absolute" style="left:42px; top:30px; width:8px; height:8px; background:#5C4632;"></span>
            <span class="absolute" style="left:48px; top:36px; width:8px; height:8px; background:#5C4632;"></span>
            <span class="absolute" style="left:54px; top:42px; width:8px; height:8px; background:#5C4632;"></span>
            @break
    @endswitch
</div>
