@props([
    'name' => 'start_time',
    'value' => '09:00 AM',
    'required' => false,
    'placeholder' => '09:00 AM',
    'label' => null,
    'id' => null,
    'model' => null,
])

@php
    $inputId = $id ?: 'watch_input_' . $name . '_' . Str::random(6);
@endphp

<div x-data="watchTimePicker('{{ addslashes($value) }}', {{ $model ? "'$model'" : 'null' }})" 
     @if($model) x-init="$watch('{{ $model }}', val => { if(val && val !== displayTime) { parseInitial(val); updateDisplay(false); } })" @endif
     class="relative w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center justify-between">
            <span>{{ $label }}</span>
            <span class="text-[10px] text-brand-600 font-semibold flex items-center gap-1 cursor-pointer hover:underline" @click="openPicker()">
                <i data-lucide="clock" class="w-3 h-3"></i> Watch Dial
            </span>
        </label>
    @endif

    <!-- Main Time Field with Watch Trigger Button -->
    <div class="relative flex items-center">
        <input type="text" 
               name="{{ $name }}" 
               id="{{ $inputId }}"
               x-ref="inputEl"
               x-model="displayTime" 
               @click="openPicker()"
               {{ $required ? 'required' : '' }}
               placeholder="{{ $placeholder }}"
               readonly
               class="w-full pl-3.5 pr-11 py-2.5 bg-slate-50 hover:bg-slate-100/80 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition shadow-sm">
        
        <button type="button" 
                @click.stop="togglePicker()" 
                class="absolute right-1.5 p-2 rounded-lg text-brand-600 hover:text-white hover:bg-brand-600 transition group"
                title="Open Interactive Watch Clock">
            <svg class="w-4 h-4 transition-transform group-hover:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </button>
    </div>

    <!-- Interactive Analog & Digital Watch Popover -->
    <div x-show="isOpen" 
         x-cloak 
         @click.away="closePicker()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
         class="absolute z-50 mt-2 left-0 sm:left-auto right-0 sm:right-auto w-[290px] sm:w-[320px] bg-slate-950 text-white rounded-3xl p-4 sm:p-5 shadow-2xl border border-slate-800 space-y-4 select-none">
        
        <!-- Top Watch Header: Digital Readout & AM/PM Switch -->
        <div class="flex items-center justify-between bg-slate-900/90 border border-slate-800/80 rounded-2xl p-2.5 sm:p-3">
            <div class="flex items-center gap-1.5">
                <!-- Hours Stepper -->
                <div class="flex flex-col items-center">
                    <button type="button" @click.prevent="stepHour(1)" class="text-slate-400 hover:text-brand-400 p-0.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <span class="text-xl sm:text-2xl font-black font-mono tracking-wider text-white" x-text="String(hour).padStart(2, '0')"></span>
                    <button type="button" @click.prevent="stepHour(-1)" class="text-slate-400 hover:text-brand-400 p-0.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <span class="text-xl sm:text-2xl font-black text-brand-400 animate-pulse">:</span>

                <!-- Minutes Stepper -->
                <div class="flex flex-col items-center">
                    <button type="button" @click.prevent="stepMinute(5)" class="text-slate-400 hover:text-accent-400 p-0.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <span class="text-xl sm:text-2xl font-black font-mono tracking-wider text-white" x-text="String(minute).padStart(2, '0')"></span>
                    <button type="button" @click.prevent="stepMinute(-5)" class="text-slate-400 hover:text-accent-400 p-0.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            </div>

            <!-- AM / PM Pill Toggle -->
            <div class="flex bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs font-bold">
                <button type="button" 
                        @click.prevent="setPeriod('AM')" 
                        :class="period === 'AM' ? 'bg-brand-600 text-white shadow' : 'text-slate-400 hover:text-white'"
                        class="px-2.5 py-1 rounded-lg transition">
                    AM
                </button>
                <button type="button" 
                        @click.prevent="setPeriod('PM')" 
                        :class="period === 'PM' ? 'bg-amber-600 text-white shadow' : 'text-slate-400 hover:text-white'"
                        class="px-2.5 py-1 rounded-lg transition">
                    PM
                </button>
            </div>
        </div>

        <!-- Luxury Interactive Analog Watch Face -->
        <div class="relative flex justify-center items-center py-1">
            <div class="relative w-44 h-44 sm:w-48 sm:h-48 rounded-full bg-gradient-to-b from-slate-900 to-slate-950 border-4 border-slate-700/80 shadow-[inset_0_2px_8px_rgba(0,0,0,0.8),0_4px_20px_rgba(0,0,0,0.5)] flex items-center justify-center">
                
                <!-- Watch Bezel Ring -->
                <div class="absolute inset-1.5 rounded-full border border-slate-800 pointer-events-none"></div>

                <!-- Clock Hour Numbers (1 through 12 clickable around dial) -->
                <template x-for="item in clockNumbers" :key="item.num">
                    <button type="button" 
                            @click.prevent="setHour(item.num)"
                            :style="`left: ${item.x}%; top: ${item.y}%;`"
                            :class="hour === item.num ? 'bg-brand-600 text-white font-extrabold shadow-md scale-110 ring-2 ring-brand-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                            class="absolute -translate-x-1/2 -translate-y-1/2 w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold transition-all duration-150 z-20"
                            x-text="item.num">
                    </button>
                </template>

                <!-- SVG Watch Hands -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none z-10" viewBox="0 0 200 200">
                    <!-- Subtle dial markings -->
                    <circle cx="100" cy="100" r="92" fill="none" stroke="#334155" stroke-width="1" stroke-dasharray="2,6"/>

                    <!-- Hour Hand (Thicker, brand colored) -->
                    <line x1="100" y1="100" x2="100" y2="48" 
                          stroke="#3b82f6" 
                          stroke-width="4.5" 
                          stroke-linecap="round"
                          :transform="`rotate(${hourAngle()} 100 100)`"
                          class="transition-transform duration-300 ease-out" />
                    
                    <!-- Minute Hand (Longer, accent colored) -->
                    <line x1="100" y1="100" x2="100" y2="28" 
                          stroke="#f59e0b" 
                          stroke-width="2.5" 
                          stroke-linecap="round"
                          :transform="`rotate(${minuteAngle()} 100 100)`"
                          class="transition-transform duration-300 ease-out" />

                    <!-- Center Pivot Cap -->
                    <circle cx="100" cy="100" r="5" fill="#f8fafc" stroke="#1e293b" stroke-width="2"/>
                    <circle cx="100" cy="100" r="2" fill="#3b82f6"/>
                </svg>
            </div>
        </div>

        <!-- Minute Fast Step Chips (:00, :15, :30, :45) -->
        <div class="space-y-1.5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Minutes:</span>
            <div class="grid grid-cols-4 gap-1.5">
                <button type="button" @click.prevent="setMinute(0)" :class="minute === 0 ? 'bg-amber-500 text-white font-extrabold' : 'bg-slate-900 text-slate-300 hover:bg-slate-800'" class="py-1 rounded-lg text-xs font-semibold transition border border-slate-800">:00</button>
                <button type="button" @click.prevent="setMinute(15)" :class="minute === 15 ? 'bg-amber-500 text-white font-extrabold' : 'bg-slate-900 text-slate-300 hover:bg-slate-800'" class="py-1 rounded-lg text-xs font-semibold transition border border-slate-800">:15</button>
                <button type="button" @click.prevent="setMinute(30)" :class="minute === 30 ? 'bg-amber-500 text-white font-extrabold' : 'bg-slate-900 text-slate-300 hover:bg-slate-800'" class="py-1 rounded-lg text-xs font-semibold transition border border-slate-800">:30</button>
                <button type="button" @click.prevent="setMinute(45)" :class="minute === 45 ? 'bg-amber-500 text-white font-extrabold' : 'bg-slate-900 text-slate-300 hover:bg-slate-800'" class="py-1 rounded-lg text-xs font-semibold transition border border-slate-800">:45</button>
            </div>
        </div>

        <!-- Quick 1-Click Slot Presets -->
        <div class="space-y-1.5 pt-1 border-t border-slate-800/80">
            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Common Slots:</span>
            <div class="grid grid-cols-4 gap-1 text-[10px]">
                <button type="button" @click.prevent="setPreset('09:00 AM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">09:00 AM</button>
                <button type="button" @click.prevent="setPreset('10:00 AM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">10:00 AM</button>
                <button type="button" @click.prevent="setPreset('11:00 AM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">11:00 AM</button>
                <button type="button" @click.prevent="setPreset('12:00 PM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">12:00 PM</button>
                <button type="button" @click.prevent="setPreset('02:00 PM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">02:00 PM</button>
                <button type="button" @click.prevent="setPreset('03:00 PM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">03:00 PM</button>
                <button type="button" @click.prevent="setPreset('04:00 PM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">04:00 PM</button>
                <button type="button" @click.prevent="setPreset('05:00 PM')" class="py-1 rounded bg-slate-900 hover:bg-brand-600 hover:text-white text-slate-300 transition border border-slate-800 font-mono">05:00 PM</button>
            </div>
        </div>

        <!-- Action Done Button -->
        <div class="pt-2 flex items-center justify-between border-t border-slate-800">
            <span class="text-xs font-mono font-bold text-emerald-400 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span x-text="displayTime"></span>
            </span>
            <button type="button" 
                    @click.prevent="closePicker()" 
                    class="px-4 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow transition">
                Done
            </button>
        </div>
    </div>
</div>
