<template>

<div class="relative group">

    <!-- Glow -->

    <div
        class="absolute -inset-6 -z-10 rounded-[40px]
               bg-gradient-to-r
               from-blue-500/20
               via-indigo-500/15
               to-violet-500/20
               blur-3xl
               transition-all
               duration-500
               group-hover:scale-105"
    ></div>

    <!-- Window -->

    <div
        class="
            overflow-hidden
            rounded-[32px]
            border
            border-slate-200/80
            bg-white/95
            backdrop-blur-xl
            shadow-[0_40px_120px_rgba(15,23,42,.18)]
            transition-all
            duration-500
            group-hover:-translate-y-2
            group-hover:shadow-[0_60px_150px_rgba(15,23,42,.22)]
        "
    >

        <slot />

    </div>

</div>

</template>