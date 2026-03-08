<div {{ $attributes->merge(['class' => 'mt-8 pt-8 border-t border-slate-100 dark:border-primary/10 text-center']) }}>
    {{ $slot }}

    <div class="mt-6 flex justify-center gap-6 text-xs text-slate-400 dark:text-slate-600">
        <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
        <a href="#" class="hover:text-primary transition-colors">Support</a>
    </div>
</div>