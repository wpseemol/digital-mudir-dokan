<?php
/**
 * FAQ Section.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

// Default FAQs
$default_faqs = [
    ['q' => 'ডেলিভারি চার্জ কত?', 'a' => 'আমাদের ডেলিভারি চার্জ ঢাকার ভেতরে ৫০ টাকা এবং ঢাকার বাইরে ১০০ টাকা।'],
    ['q' => 'পণ্য রিটার্ন করা যাবে?', 'a' => 'হ্যাঁ, পণ্য পাওয়ার ২৪ ঘণ্টার মধ্যে সমস্যা জানালে আমরা রিটার্ন বা পরিবর্তন করে থাকি।'],
    ['q' => 'আপনারা কি ক্যাশ অন ডেলিভারি দেন?', 'a' => 'হ্যাঁ, আমরা সারা বাংলাদেশে ক্যাশ অন ডেলিভারি দিয়ে থাকি।'],
];

$faqs = [];
for ( $i = 1; $i <= 5; $i++ ) {
    $q = get_theme_mod( "dmd_faq_{$i}_q" );
    $a = get_theme_mod( "dmd_faq_{$i}_a" );
    
    // Use saved customizer value if exists, otherwise fallback to defaults for first 3
    if ( $q && $a ) {
        $faqs[] = ['q' => $q, 'a' => $a];
    } elseif ( isset($default_faqs[$i-1]) ) {
        $faqs[] = $default_faqs[$i-1];
    }
}

if ( empty( $faqs ) ) {
    return; // Don't show if empty
}
?>
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <h3 class="text-emerald-700 font-semibold mb-2">সচরাচর জিজ্ঞাসিত প্রশ্নাবলী</h3>
            <h2 class="text-3xl font-bold text-ink">সাধারণ প্রশ্ন ও উত্তর (FAQ)</h2>
        </div>
        
        <div class="space-y-4">
            <?php foreach ( $faqs as $index => $faq ) : ?>
                <div class="faq-item bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:border-emerald-500 transition-all cursor-pointer">
                    <div class="flex justify-between items-center font-semibold text-ink">
                        <span><?php echo esc_html( $faq['q'] ); ?></span>
                        <span class="faq-icon text-xl text-emerald-600">+</span>
                    </div>
                    <div class="faq-answer hidden mt-3 text-muted text-sm pt-3 border-t border-gray-100">
                        <?php echo esc_html( $faq['a'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
