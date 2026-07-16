<x-app-layout>

    {{-- ══════════════════════════════════════════
         LIVING FARM BANNER v2 - Full Width Scene
    ══════════════════════════════════════════ --}}
    <div
        x-data="bannerScene()"
        x-init="init()"
        :class="chirping && 'is-chirp'"
        class="relative w-full overflow-hidden mb-6 border-2 border-soil-dark shadow-cozy-lg"
        style="height: 200px; border-radius: 4px;"
    >
        {{-- ░░░ LAYER 1 - SKY ░░░ --}}
        <div class="absolute inset-0 transition-colors duration-1000" :class="skyClass"></div>

        {{-- ✨ Stars (night only) --}}
        <template x-if="!isDaytime">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute anim-shimmer bg-white" style="top:12px; left:5%;  width:2px; height:2px;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:24px; left:12%; width:2px; height:2px; animation-delay:0.4s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:14px; left:22%; width:3px; height:3px; animation-delay:0.8s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:32px; left:32%; width:2px; height:2px; animation-delay:1.2s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:18px; left:42%; width:2px; height:2px; animation-delay:0.2s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:28px; left:55%; width:3px; height:3px; animation-delay:1.6s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:14px; left:68%; width:2px; height:2px; animation-delay:0.6s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:36px; left:78%; width:2px; height:2px; animation-delay:0.9s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:22px; left:88%; width:3px; height:3px; animation-delay:1.4s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:46px; left:96%; width:2px; height:2px; animation-delay:0.3s;"></div>
            </div>
        </template>

        {{-- ░░░ LAYER 2 - Mountain Range stair-stepped ░░░ --}}
        <div class="absolute pointer-events-none" style="bottom: 56px; left:0; right:0; height: 62px;">
            <div class="px-mtn" style="left:-3%;  bottom:0; width:150px; height:50px; background:#6E8B68; opacity:.6;"></div>
            <div class="px-mtn" style="left:13%;  bottom:0; width:200px; height:62px; background:#5C7A56; opacity:.65;"></div>
            <div class="px-mtn" style="left:37%;  bottom:0; width:140px; height:46px; background:#6E8B68; opacity:.6;"></div>
            <div class="px-mtn" style="right:16%; bottom:0; width:180px; height:58px; background:#5C7A56; opacity:.65;"></div>
            <div class="px-mtn" style="right:-4%; bottom:0; width:150px; height:48px; background:#6E8B68; opacity:.6;"></div>
        </div>

        {{-- ░░░ LAYER 3 - Distant hills, undakan kotak ░░░ --}}
        <div class="absolute bottom-10 left-0 right-0 pointer-events-none">
            <div class="absolute" style="bottom:0; left:-5%; width:200px; height:44px; opacity:.55;">
                <span class="absolute inset-x-0 bottom-0" style="height:18px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:12%; right:12%; bottom:18px; height:14px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:28%; right:28%; bottom:32px; height:12px; background:#4E7D4C;"></span>
            </div>
            <div class="absolute" style="bottom:0; left:30%; width:260px; height:56px; opacity:.65;">
                <span class="absolute inset-x-0 bottom-0" style="height:24px; background:#3D5A3A;"></span>
                <span class="absolute" style="left:12%; right:12%; bottom:24px; height:18px; background:#3D5A3A;"></span>
                <span class="absolute" style="left:28%; right:28%; bottom:42px; height:14px; background:#3D5A3A;"></span>
            </div>
            <div class="absolute" style="bottom:0; right:-5%; width:180px; height:42px; opacity:.55;">
                <span class="absolute inset-x-0 bottom-0" style="height:18px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:14%; right:14%; bottom:18px; height:13px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:30%; right:30%; bottom:31px; height:11px; background:#4E7D4C;"></span>
            </div>
        </div>

        {{-- ░░░ LAYER 4 - Near hills, undakan kotak ░░░ --}}
        <div class="absolute bottom-6 left-0 right-0 pointer-events-none">
            <div class="absolute" style="bottom:0; left:8%; width:130px; height:34px;">
                <span class="absolute inset-x-0 bottom-0" style="height:14px; background:#5C8F58;"></span>
                <span class="absolute" style="left:14%; right:14%; bottom:14px; height:11px; background:#5C8F58;"></span>
                <span class="absolute" style="left:30%; right:30%; bottom:25px; height:9px; background:#5C8F58;"></span>
            </div>
            <div class="absolute" style="bottom:0; left:55%; width:160px; height:40px;">
                <span class="absolute inset-x-0 bottom-0" style="height:16px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:13%; right:13%; bottom:16px; height:13px; background:#4E7D4C;"></span>
                <span class="absolute" style="left:29%; right:29%; bottom:29px; height:11px; background:#4E7D4C;"></span>
            </div>
        </div>

        {{-- ░░░ ANGIN: daun terbang + garis hembusan ░░░ --}}
        <div class="wb-leaf" style="top:36px;  width:6px; height:6px; background:#8FBC8A; animation-duration:17s;"></div>
        <div class="wb-leaf" style="top:62px;  width:5px; height:5px; background:#6BA368; animation-duration:21s; animation-delay:4s;"></div>
        <div class="wb-leaf" style="top:84px;  width:6px; height:6px; background:#E5B567; animation-duration:25s; animation-delay:9s;"></div>
        <div class="wb-leaf" style="top:50px;  width:4px; height:4px; background:#8FBC8A; animation-duration:19s; animation-delay:13s;"></div>
        <div class="wb-leaf" style="top:104px; width:5px; height:5px; background:#C99845; animation-duration:23s; animation-delay:6s;"></div>
        <div class="wb-wind" style="top:44px;"></div>
        <div class="wb-wind" style="top:78px; animation-delay:5.5s;"></div>

        {{-- ═══════ LEFT THIRD ═══════ --}}

        {{-- ⚙ Pixel Windmill --}}
        <div class="absolute" style="bottom: 36px; left: 4%;">
            {{-- Tower --}}
            <div style="width: 6px; height: 50px; background: linear-gradient(180deg, #E8DEC4 0%, #B5AFA8 100%); border: 1px solid #5C4632; margin: 0 auto;"></div>
            {{-- Blades hub --}}
            <div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); width: 14px; height: 14px;">
                <div class="anim-windmill" style="position: relative; width: 100%; height: 100%;">
                    <div style="position:absolute; top:6px; left:-18px; width:20px; height:3px; background:#5C4632;"></div>
                    <div style="position:absolute; top:6px; right:-18px; width:20px; height:3px; background:#5C4632;"></div>
                    <div style="position:absolute; left:6px; top:-18px; width:3px; height:20px; background:#5C4632;"></div>
                    <div style="position:absolute; left:6px; bottom:-18px; width:3px; height:20px; background:#5C4632;"></div>
                    <div style="position:absolute; top:5px; left:5px; width:5px; height:5px; background:#9A3F56; border:1px solid #5C4632; border-radius:50%;"></div>
                </div>
            </div>
            {{-- Base --}}
            <div style="width: 14px; height: 5px; background: #8B5A2B; border: 1px solid #5C4632; margin: 0 auto; margin-top: -1px;"></div>
        </div>

        {{-- 🌳 Tree (large, swaying) --}}
        <div class="absolute" style="bottom: 30px; left: 14%;">
            <div style="width:7px; height:18px; background:#5C4632; margin:0 auto;"></div>
            <div class="anim-sway" style="width:32px; height:24px; background:#4E7D4C; margin-top:-3px;
                        box-shadow: 0 -6px 0 #4E7D4C, 6px 6px 0 -2px #5C8F58, -6px 4px 0 -3px #5C8F58;"></div>
        </div>

        {{-- 🌸 Bushes left (blocky) --}}
        <div class="absolute" style="bottom: 18px; left: 22%; width: 16px; height: 8px;">
            <span class="absolute inset-x-0 bottom-0" style="height:5px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:3px; right:3px; bottom:5px; height:3px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:-6px; bottom:0; width:5px; height:4px; background:#5C8F58;"></span>
            <span class="absolute" style="right:-6px; bottom:0; width:5px; height:4px; background:#5C8F58;"></span>
        </div>

        {{-- 🌼 Hay bale stack --}}
        <div class="absolute" style="bottom: 22px; left: 27%;">
            <div style="width:14px; height:8px; background:#E5B567; border:1px solid #C99845;
                        box-shadow: inset 0 -2px 0 #D4A95C;"></div>
            <div style="width:14px; height:8px; background:#E5B567; border:1px solid #C99845; margin-top:-1px;
                        box-shadow: inset 0 -2px 0 #D4A95C;"></div>
        </div>

        {{-- ═══════ CENTER ═══════ --}}

        {{-- 🌳 Tree (small mid) --}}
        <div class="absolute" style="bottom: 26px; left: 34%;">
            <div style="width:5px; height:14px; background:#5C4632; margin:0 auto;"></div>
            <div class="anim-sway" style="width:24px; height:18px; background:#4E7D4C; margin-top:-2px; animation-delay:0.5s;"></div>
        </div>

        {{-- 💧 Pixel Pond (kotak berundak) --}}
        <div class="absolute" style="bottom: 18px; left: 39%; width:40px; height:12px;">
            <span class="absolute" style="left:4px; right:4px; top:0; height:4px; background:#77AADD;"></span>
            <span class="absolute" style="left:0; right:0; top:4px; height:5px; background:#77AADD;"></span>
            <span class="absolute" style="left:4px; right:4px; top:9px; height:3px; background:#4E7AA8;"></span>
            <span class="anim-pond absolute" style="top:4px; left:8px; width:18px; height:2px; background:#A8CDED;"></span>
            {{-- Lily pad --}}
            <span class="absolute" style="top:2px; left:24px; width:6px; height:3px; background:#4E7D4C;"></span>
        </div>

        {{-- 🪨 Stones around pond --}}
        <div class="absolute" style="bottom: 20px; left: 38%; width: 4px; height: 3px; background:#A9A39E; border:1px solid #7B7672;"></div>
        <div class="absolute" style="bottom: 19px; left: 46%; width: 5px; height: 3px; background:#A9A39E; border:1px solid #7B7672;"></div>

        {{-- 🪵 Wooden Sign with greeting (center pinned in grass) --}}
        <div class="absolute" style="bottom: 26px; left: 50%; transform: translateX(-50%); z-index: 6;">
            {{-- Posts --}}
            <div style="position:absolute; bottom:0; left:-1px; width:3px; height:16px; background:#5C4632;"></div>
            <div style="position:absolute; bottom:0; right:-1px; width:3px; height:16px; background:#5C4632;"></div>
            {{-- Sign board --}}
            <div style="position:relative; padding:3px 8px; background:#D4A373; border:2px solid #5C4632; box-shadow: 0 1px 0 #5C4632;">
                <p class="font-pixel" style="font-size: 7px; color:#5C4632; letter-spacing:0.05em; white-space:nowrap;"
                   x-text="`SELAMAT ${greeting.toUpperCase()}!`"></p>
            </div>
        </div>

        {{-- ═══════ POHON SIUL + BURUNG (klik untuk bersiul) ═══════ --}}
        <div class="absolute" style="bottom: 16px; left: 56%; width: 140px; height: 110px;">
            {{-- tajuk berundak --}}
            <div class="absolute" style="left:30px; top:0;    width:36px; height:10px; background:#8FBC8A;"></div>
            <div class="absolute" style="left:20px; top:8px;  width:58px; height:15px; background:#6BA368;"></div>
            <div class="absolute" style="left:8px;  top:21px; width:80px; height:22px; background:#4E7D4C;"></div>
            <div class="absolute" style="left:0;    top:33px; width:22px; height:13px; background:#4E7D4C;"></div>
            <div class="absolute" style="left:79px; top:33px; width:22px; height:13px; background:#4E7D4C;"></div>
            {{-- batang + dahan tempat bertengger --}}
            <div class="absolute" style="left:36px;  top:41px; width:14px; height:69px; background:#83644A;"></div>
            <div class="absolute" style="left:45px;  top:41px; width:5px;  height:69px; background:#6E5138;"></div>
            <div class="absolute" style="left:49px;  top:53px; width:62px; height:5px;  background:#83644A;"></div>
            <div class="absolute" style="left:49px;  top:58px; width:62px; height:2px;  background:#6E5138;"></div>
            <div class="absolute" style="left:111px; top:54px; width:8px;  height:4px;  background:#83644A;"></div>

            {{-- burung pipit --}}
            <button type="button" class="wb-bird" style="left:60px; top:10px; width:54px; height:45px;"
                    @click.stop="chirp()" title="Klik untuk bersiul" aria-label="Burung pipit, klik untuk bersiul">
                <svg viewBox="0 0 24 20" width="54" height="45">
                    <g fill="#3E2F22">
                        <rect x="11" y="0" width="6" height="1"/><rect x="10" y="1" width="1" height="1"/><rect x="17" y="1" width="1" height="1"/>
                        <rect x="9" y="2" width="1" height="1"/><rect x="18" y="2" width="1" height="1"/>
                        <rect x="8" y="3" width="1" height="1"/><rect x="19" y="3" width="1" height="1"/>
                        <rect x="8" y="4" width="1" height="1"/><rect x="14" y="4" width="2" height="1"/><rect x="19" y="4" width="1" height="1"/>
                        <rect x="7" y="5" width="1" height="1"/><rect x="14" y="5" width="2" height="1"/><rect x="19" y="5" width="1" height="1"/>
                        <rect x="7" y="6" width="1" height="1"/><rect x="19" y="6" width="1" height="1"/>
                        <rect x="6" y="7" width="1" height="1"/><rect x="19" y="7" width="1" height="1"/>
                        <rect x="5" y="8" width="1" height="1"/><rect x="19" y="8" width="1" height="1"/>
                        <rect x="4" y="9" width="1" height="1"/><rect x="19" y="9" width="1" height="1"/>
                        <rect x="3" y="10" width="1" height="1"/><rect x="19" y="10" width="1" height="1"/>
                        <rect x="2" y="11" width="1" height="1"/><rect x="19" y="11" width="1" height="1"/>
                        <rect x="1" y="12" width="1" height="1"/><rect x="18" y="12" width="1" height="1"/>
                        <rect x="0" y="13" width="1" height="1"/><rect x="17" y="13" width="1" height="1"/>
                        <rect x="0" y="14" width="1" height="1"/><rect x="16" y="14" width="1" height="1"/>
                        <rect x="1" y="15" width="2" height="1"/><rect x="14" y="15" width="2" height="1"/>
                        <rect x="3" y="16" width="2" height="1"/><rect x="10" y="16" width="5" height="1"/>
                        <rect x="5" y="17" width="4" height="1"/>
                    </g>
                    <g fill="#83644A">
                        <rect x="11" y="1" width="6" height="1"/><rect x="10" y="2" width="8" height="1"/>
                        <rect x="9" y="3" width="4" height="1"/><rect x="18" y="3" width="1" height="1"/>
                        <rect x="9" y="4" width="3" height="1"/><rect x="8" y="5" width="3" height="1"/><rect x="8" y="6" width="3" height="1"/>
                        <rect x="7" y="7" width="2" height="1"/><rect x="11" y="7" width="2" height="1"/>
                        <rect x="6" y="8" width="1" height="1"/><rect x="12" y="8" width="2" height="1"/>
                        <rect x="5" y="9" width="1" height="1"/><rect x="13" y="9" width="1" height="1"/>
                        <rect x="4" y="10" width="2" height="1"/>
                        <rect x="3" y="11" width="2" height="1"/><rect x="13" y="11" width="1" height="1"/>
                        <rect x="2" y="12" width="1" height="1"/><rect x="4" y="12" width="1" height="1"/><rect x="12" y="12" width="1" height="1"/>
                        <rect x="1" y="13" width="1" height="1"/><rect x="4" y="13" width="2" height="1"/><rect x="11" y="13" width="1" height="1"/>
                        <rect x="4" y="14" width="7" height="1"/><rect x="6" y="15" width="5" height="1"/><rect x="8" y="16" width="2" height="1"/>
                    </g>
                    <g fill="#5C4632">
                        <rect x="3" y="12" width="1" height="1"/><rect x="2" y="13" width="2" height="1"/>
                        <rect x="1" y="14" width="3" height="1"/><rect x="3" y="15" width="3" height="1"/><rect x="5" y="16" width="3" height="1"/>
                    </g>
                    <g fill="#A88B6E">
                        <rect x="13" y="10" width="1" height="1"/><rect x="5" y="11" width="1" height="1"/><rect x="12" y="11" width="1" height="1"/>
                        <rect x="5" y="12" width="1" height="1"/><rect x="11" y="12" width="1" height="1"/><rect x="6" y="13" width="5" height="1"/>
                    </g>
                    <g fill="#FBF7EC">
                        <rect x="13" y="3" width="5" height="1"/><rect x="12" y="4" width="2" height="1"/><rect x="16" y="4" width="3" height="1"/>
                        <rect x="11" y="5" width="3" height="1"/><rect x="16" y="5" width="3" height="1"/><rect x="11" y="6" width="8" height="1"/>
                        <rect x="13" y="7" width="6" height="1"/><rect x="11" y="9" width="2" height="1"/>
                        <rect x="6" y="10" width="7" height="1"/><rect x="6" y="11" width="6" height="1"/><rect x="6" y="12" width="5" height="1"/>
                    </g>
                    <g fill="#E5B567">
                        <rect x="9" y="7" width="2" height="1"/><rect x="7" y="8" width="5" height="1"/><rect x="6" y="9" width="5" height="1"/>
                    </g>
                    <g fill="#A8CDED">
                        <rect x="14" y="8" width="5" height="1"/><rect x="14" y="9" width="5" height="1"/><rect x="14" y="10" width="5" height="1"/>
                        <rect x="14" y="11" width="5" height="1"/><rect x="13" y="12" width="5" height="1"/><rect x="12" y="13" width="5" height="1"/>
                        <rect x="11" y="14" width="5" height="1"/><rect x="11" y="15" width="3" height="1"/>
                    </g>
                    <g fill="#9A6A3D">
                        <rect x="10" y="17" width="1" height="1"/><rect x="14" y="17" width="1" height="1"/>
                        <rect x="10" y="18" width="1" height="1"/><rect x="14" y="18" width="1" height="1"/>
                        <rect x="9" y="19" width="2" height="1"/><rect x="14" y="19" width="2" height="1"/>
                    </g>
                    <g class="wb-beak wb-beak-closed" fill="#3E2F22">
                        <rect x="20" y="5" width="2" height="1"/><rect x="20" y="6" width="1" height="1"/>
                    </g>
                    <g class="wb-beak wb-beak-open" fill="#3E2F22">
                        <rect x="20" y="5" width="2" height="1"/><rect x="21" y="6" width="2" height="1"/>
                    </g>
                </svg>
            </button>

            {{-- notes siulan --}}
            <span class="wb-note" style="left:114px; top:10px;">
                <svg viewBox="0 0 8 9" width="16" height="18" fill="#3E2F22">
                    <rect x="4" y="0" width="1" height="1"/><rect x="4" y="1" width="2" height="1"/>
                    <rect x="4" y="2" width="1" height="1"/><rect x="6" y="2" width="1" height="1"/>
                    <rect x="4" y="3" width="1" height="1"/><rect x="4" y="4" width="1" height="1"/>
                    <rect x="2" y="5" width="3" height="1"/><rect x="1" y="6" width="4" height="1"/>
                    <rect x="1" y="7" width="4" height="1"/><rect x="2" y="8" width="2" height="1"/>
                </svg>
            </span>
            <span class="wb-note n2" style="left:128px; top:0;">
                <svg viewBox="0 0 10 7" width="20" height="14" fill="#3E2F22">
                    <rect x="1" y="0" width="7" height="1"/>
                    <rect x="1" y="1" width="1" height="1"/><rect x="7" y="1" width="1" height="1"/>
                    <rect x="1" y="2" width="1" height="1"/><rect x="7" y="2" width="1" height="1"/>
                    <rect x="1" y="3" width="1" height="1"/><rect x="7" y="3" width="1" height="1"/>
                    <rect x="0" y="4" width="3" height="1"/><rect x="6" y="4" width="3" height="1"/>
                    <rect x="0" y="5" width="4" height="1"/><rect x="5" y="5" width="4" height="1"/>
                    <rect x="1" y="6" width="2" height="1"/><rect x="6" y="6" width="2" height="1"/>
                </svg>
            </span>
        </div>

        {{-- ═══════ KEBUN WARNA: jagung, berry, barisan bunga ═══════ --}}
        <div class="absolute" style="bottom: 20px; left: 30%;">
            <span class="absolute" style="bottom:0; width:3px; height:9px; background:#4E7D4C; box-shadow: 7px 0 0 #4E7D4C, 14px 1px 0 #4E7D4C;"></span>
            <span class="absolute" style="bottom:7px; left:-1px; width:5px; height:6px; background:#E5B567; box-shadow: 7px 1px 0 #F1CC8E, 14px 0 0 #E5B567;"></span>
        </div>
        <div class="absolute" style="bottom: 20px; left: 47%;">
            <span class="absolute" style="bottom:0; width:14px; height:7px; background:#4E7D4C;"></span>
            <span class="absolute" style="bottom:3px; left:2px; width:3px; height:3px; background:#BE546E; box-shadow: 5px 1px 0 #9A3F56, 9px -1px 0 #BE546E;"></span>
        </div>
        <div class="absolute" style="bottom: 19px; left: 69%;">
            <span class="absolute" style="bottom:4px; width:4px; height:4px; background:#BE546E; box-shadow: 9px 1px 0 #77AADD, 18px -1px 0 #E5B567, 27px 1px 0 #D4869A, 36px 0 0 #F1CC8E;"></span>
            <span class="absolute" style="bottom:0; left:1px; width:2px; height:4px; background:#4E7D4C; box-shadow: 9px 1px 0 #4E7D4C, 18px -1px 0 #4E7D4C, 27px 1px 0 #4E7D4C, 36px 0 0 #4E7D4C;"></span>
        </div>

        {{-- ═══════ RIGHT THIRD ═══════ --}}

        {{-- 🏗 Silo (next to house) --}}
        <div class="absolute" style="bottom: 30px; right: 22%;">
            {{-- Conical roof --}}
            <div style="width:0; height:0; border-left:10px solid transparent; border-right:10px solid transparent; border-bottom:8px solid #7B7672; margin: 0 auto;"></div>
            {{-- Silo body --}}
            <div style="width: 16px; height: 36px; background: linear-gradient(90deg, #C9C4C0 0%, #E8DEC4 50%, #C9C4C0 100%); border: 1px solid #5C4632;">
                <div style="width:100%; height:3px; background:#7B7672; margin-top: 6px;"></div>
                <div style="width:100%; height:3px; background:#7B7672; margin-top: 8px;"></div>
                <div style="width:100%; height:3px; background:#7B7672; margin-top: 8px;"></div>
            </div>
        </div>

        {{-- 🏡 Farmhouse with chimney smoke --}}
        <div class="absolute" style="bottom: 30px; right: 8%;">
            {{-- Smoke particles (kotak) --}}
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.6;"></div>
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.5; animation-delay:1s;"></div>
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.4; animation-delay:2s;"></div>
            {{-- Chimney --}}
            <div style="position:absolute; bottom:24px; left:6px; width:6px; height:10px; background:#5C4632;"></div>
            {{-- Roof (triangle via borders) --}}
            <div style="width:0; height:0; border-left:18px solid transparent; border-right:18px solid transparent; border-bottom:14px solid #9A3F56; position:absolute; bottom:24px; left:-2px;"></div>
            {{-- Body --}}
            <div style="width:32px; height:24px; background:#D4A373; border:2px solid #5C4632; position:relative;">
                {{-- Door --}}
                <div style="position:absolute; bottom:0; left:11px; width:8px; height:12px; background:#5C4632;"></div>
                {{-- Window --}}
                <div style="position:absolute; top:4px; left:4px; width:6px; height:6px; background:#F1CC8E; border:1px solid #5C4632;"
                     :class="!isDaytime ? 'anim-shimmer' : ''"></div>
            </div>
        </div>

        {{-- 🏮 Lantern (always glows at night, dim in day) --}}
        <div class="absolute" style="bottom: 28px; right: 18%;">
            <div style="width: 2px; height: 18px; background: #5C4632; margin: 0 auto;"></div>
            <div :class="!isDaytime ? 'anim-lantern' : ''"
                 :style="!isDaytime
                    ? 'width: 8px; height: 9px; background:#F1CC8E; border:1px solid #5C4632; margin: -2px auto 0;'
                    : 'width: 8px; height: 9px; background:#A88B6E; border:1px solid #5C4632; margin: -2px auto 0;'"></div>
        </div>

        {{-- 🌸 Bushes right (blocky) --}}
        <div class="absolute" style="bottom: 18px; right: 30%; width: 14px; height: 7px;">
            <span class="absolute inset-x-0 bottom-0" style="height:4px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:3px; right:3px; bottom:4px; height:3px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:-5px; bottom:0; width:4px; height:3px; background:#5C8F58;"></span>
        </div>
        <div class="absolute" style="bottom: 18px; right: 4%; width: 16px; height: 8px;">
            <span class="absolute inset-x-0 bottom-0" style="height:5px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:3px; right:3px; bottom:5px; height:3px; background:#4E7D4C;"></span>
            <span class="absolute" style="left:-6px; bottom:0; width:5px; height:4px; background:#5C8F58;"></span>
        </div>

        {{-- 🌼 Small flowers scattered (always visible) --}}
        <div class="absolute" style="bottom: 19px; right: 14%;">
            <div style="width:3px; height:3px; background:#BE546E; border-radius:50%; box-shadow:0 0 0 1px #5C4632;"></div>
        </div>
        <div class="absolute" style="bottom: 19px; right: 26%;">
            <div style="width:3px; height:3px; background:#E5B567; border-radius:50%; box-shadow:0 0 0 1px #5C4632;"></div>
        </div>
        <div class="absolute" style="bottom: 19px; left: 32%;">
            <div style="width:3px; height:3px; background:#BE546E; border-radius:50%; box-shadow:0 0 0 1px #5C4632;"></div>
        </div>

        {{-- ✨ Fireflies (night only) --}}
        <template x-if="!isDaytime">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute anim-firefly" style="bottom:60px; left:8%;  width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E;"></div>
                <div class="absolute anim-firefly" style="bottom:80px; left:24%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:1.5s;"></div>
                <div class="absolute anim-firefly" style="bottom:65px; left:44%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:0.8s;"></div>
                <div class="absolute anim-firefly" style="bottom:75px; left:62%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:2.2s;"></div>
                <div class="absolute anim-firefly" style="bottom:55px; right:12%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:1s;"></div>
                <div class="absolute anim-firefly" style="bottom:70px; right:30%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:0.4s;"></div>
            </div>
        </template>

        {{-- ░░░ LAYER 5 - Picket Fence ░░░ --}}
        <div class="absolute" style="bottom: 18px; left: 0; right: 0; height: 10px; pointer-events:none;">
            <div style="height: 100%; background: repeating-linear-gradient(
                90deg,
                transparent 0,
                transparent 16px,
                #5C4632 16px,
                #5C4632 19px,
                transparent 19px,
                transparent 22px
            );"></div>
            <div style="position:absolute; top:3px; left:0; right:0; height:2px; background:#5C4632;"></div>
            <div style="position:absolute; top:7px; left:0; right:0; height:2px; background:#5C4632;"></div>
        </div>

        {{-- ░░░ LAYER 6 - Stone path crossing the dirt ░░░ --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none" style="height: 18px;">
            <div style="position:absolute; bottom:4px; left:12%;  width:6px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:8px; left:22%;  width:7px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:3px; left:34%;  width:6px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:7px; left:48%;  width:7px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:4px; left:60%;  width:6px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:8px; left:72%;  width:7px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
            <div style="position:absolute; bottom:3px; left:84%;  width:6px; height:5px; background:#C9C4C0; border:1px solid #7B7672;"></div>
        </div>

        {{-- ░░░ LAYER 7 - Ground stripes (dirt) ░░░ --}}
        <div class="absolute bottom-0 left-0 right-0" style="height: 18px;
             background: repeating-linear-gradient(90deg, #6B4E32 0, #6B4E32 14px, #5C4632 14px, #5C4632 28px);
             border-top: 3px solid #4E7D4C;"></div>

        {{-- ░░░ TITLE OVERLAY ░░░ --}}
        <div class="absolute top-3 left-0 right-0 z-10 px-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="font-pixel inline-block"
                        style="font-size: clamp(11px, 2.4vw, 17px); color: #FBF7EC;
                               background:#3E5A78; border:2px solid #2E4459; padding:4px 10px;
                               box-shadow: 2px 2px 0 rgba(0,0,0,0.3); letter-spacing: 0.05em;">
                        LIFE-SIM <span style="color:#F1CC8E">DASHBOARD</span>
                    </h1>
                    <p class="font-sans text-xs mt-1.5 inline-block px-2.5 py-1 bg-black/25 backdrop-blur-sm"
                       style="color: #FBF7EC; border-radius: 999px; letter-spacing: 0.03em;">
                        {{ Auth::user()->name }}'s Farm
                    </p>
                </div>

                {{-- Mini flag pole (decorative) --}}
                <div class="hidden sm:block relative" style="margin-top: 4px;">
                    <div style="width:3px; height:48px; background:#5C4632;"></div>
                    <div class="anim-flag" style="position:absolute; left:3px; top:0; width:24px; height:14px; background:#BE546E; border:1px solid #5C4632;"></div>
                    <div class="anim-flag" style="position:absolute; left:3px; top:16px; width:18px; height:10px; background:#E5B567; border:1px solid #5C4632; animation-delay:0.4s;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SUMMARY WIDGETS
    ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <div>@livewire('quest-summary')</div>
        <div>@livewire('finance-summary')</div>
    </div>

    <div>@livewire('library-summary')</div>

    <script>
    function bannerScene() {
        return {
            hour: new Date().getHours(),
            skyClass: 'sky-day', isDaytime: true,
            greeting: 'datang',
            chirping: false, chirpTimer: null,
            init() {
                this.update();
                setInterval(() => this.update(), 60000);
                this.scheduleChirp();
            },
            chirp() {
                if (this.chirping) return;
                this.chirping = true;
                window.dispatchEvent(new CustomEvent('bird-chirp'));
                setTimeout(() => { this.chirping = false; this.scheduleChirp(); }, 1400);
            },
            scheduleChirp() {
                clearTimeout(this.chirpTimer);
                this.chirpTimer = setTimeout(() => this.chirp(), 8000 + Math.random() * 7000);
            },
            update() {
                this.hour = new Date().getHours();
                this.isDaytime = this.hour >= 6 && this.hour < 18;
                if (this.hour >= 5 && this.hour < 10) {
                    this.skyClass = 'sky-morning'; this.greeting = 'pagi';
                } else if (this.hour >= 10 && this.hour < 16) {
                    this.skyClass = 'sky-day'; this.greeting = 'siang';
                } else if (this.hour >= 16 && this.hour < 19) {
                    this.skyClass = 'sky-evening'; this.greeting = 'sore';
                } else {
                    this.skyClass = 'sky-night'; this.greeting = 'malam';
                }
            }
        }
    }
    </script>

</x-app-layout>
