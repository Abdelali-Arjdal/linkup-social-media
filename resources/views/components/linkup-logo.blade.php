@props(['class' => 'w-12 h-12'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Left Head (Blue to Teal gradient) -->
    <path d="M25 55 C25 42, 30 30, 45 25 C50 23, 55 22, 60 25 C70 30, 75 40, 75 50 C75 55, 73 58, 70 60 C73 65, 75 70, 75 75 C75 85, 70 95, 60 100 C55 102, 50 103, 45 100 C30 95, 25 82, 25 65 Z" fill="url(#gradientLeft)"/>
    
    <!-- Right Head (Teal to Blue gradient) -->
    <path d="M95 55 C95 42, 90 30, 75 25 C70 23, 65 22, 60 25 C50 30, 45 40, 45 50 C45 55, 47 58, 50 60 C47 65, 45 70, 45 75 C45 85, 50 95, 60 100 C65 102, 70 103, 75 100 C90 95, 95 82, 95 65 Z" fill="url(#gradientRight)"/>
    
    <!-- Speech Bubble Left -->
    <path d="M25 75 C25 80, 30 85, 35 85 L45 90 L45 85 C50 85, 55 80, 55 75 L55 70 C55 65, 50 60, 45 60 C40 60, 35 65, 35 70 Z" fill="url(#gradientLeft)" opacity="0.9"/>
    
    <!-- Speech Bubble Right -->
    <path d="M95 75 C95 80, 90 85, 85 85 L75 90 L75 85 C70 85, 65 80, 65 75 L65 70 C65 65, 70 60, 75 60 C80 60, 85 65, 85 70 Z" fill="url(#gradientRight)" opacity="0.9"/>
    
    <!-- Gradients -->
    <defs>
        <linearGradient id="gradientLeft" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#007BFF;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#00C897;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#00C897;stop-opacity:0.9" />
        </linearGradient>
        <linearGradient id="gradientRight" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#00C897;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#007BFF;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#007BFF;stop-opacity:0.9" />
        </linearGradient>
    </defs>
</svg>
