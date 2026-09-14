<?php
/**
 * FAQ Section.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$faqs = [
    ['q' => 'ডেলিভারি চার্জ কত?', 'a' => 'আমাদের ডেলিভারি চার্জ এলাকাভেদে ভিন্ন হতে পারে। সাধারণত শহরের ভেতরে ৩০ টাকা এবং শহরের বাইরে ৬০ টাকা থেকে শুরু।'],
    ['q' => 'কত দিনে ডেলিভারি পাবো?', 'a' => 'আমরা অর্ডার কনফার্ম হওয়ার ১-৩ কার্যদিবসের মধ্যে ডেলিভারি প্রদান করে থাকি।'],
    ['q' => 'পণ্য পছন্দ না হলে কি রিটার্ন করা যাবে?', 'a' => 'হ্যাঁ, পণ্য পাওয়ার পর কোনো সমস্যা থাকলে ২৪ ঘণ্টার মধ্যে আমাদের সাথে যোগাযোগ করুন, আমরা প্রয়োজনীয় ব্যবস্থা নেব।'],
    ['q' => 'ক্যাশ অন ডেলিভারি সুবিধা আছে কি?', 'a' => 'হ্যাঁ, আমাদের সকল পণ্যে ক্যাশ অন ডেলিভারি সুবিধা রয়েছে।'],
    ['q' => '১০০% খাঁটি পণ্যের নিশ্চয়তা কীভাবে দিচ্ছেন?', 'a' => 'আমরা সরাসরি কৃষকদের কাছ থেকে পণ্য সংগ্রহ করি এবং গুণমান নিশ্চিত করে সরবরাহ করি।'],
];
?>
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <h3 class="text-green-600 font-semibold mb-2">সচরাচর জিজ্ঞাসিত প্রশ্নাবলী</h3>
            <h2 class="text-3xl font-bold text-ink">সাধারণ প্রশ্ন ও উত্তর (FAQ)</h2>
        </div>
        
        <div class="space-y-4">
            <?php foreach ( $faqs as $index => $faq ) : ?>
                <div class="faq-item bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:border-emerald-500 transition-all cursor-pointer">
                    <div class="flex justify-between items-center font-semibold text-ink">
                        <span><?php echo esc_html( $faq['q'] ); ?></span>
                        <span class="faq-icon text-xl text-green-600">+</span>
                    </div>
                    <div class="faq-answer hidden mt-3 text-muted text-sm pt-3 border-t border-gray-100">
                        <?php echo esc_html( $faq['a'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
