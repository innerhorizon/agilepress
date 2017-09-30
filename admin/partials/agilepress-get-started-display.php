<?php
/**
 * Presentation code for wizard screen(s)
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\admin\partials
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

?>
<div>
<img src="<?= plugins_url('agilepress') ?>/admin/partials/img/agilepress.png">
<h2>Welcome to AgilePress!</h2>
<p>First things first... AgilePress is like a roller-coaster: it has a back-end and you're welcome to use it, but it's a lot more fun up front. The best thing about AgilePress are the boards, and there isn't much that you can't do from the boards once they're set up. Which is why we want to mention, right up front:</p>
<ul>
    <li>- Boards live on pages;</li>
    <li>- Pages should be full-width whenever possible;</li>
    <li>- You need a product go get started.</li>
</ul>
<h3>Create a Product</h3>
<p>The first thing you need to do is create a product. A product is the output of your process; it could be building a deck, picking the best car for your next purchase, writing a term paper, or - of course - developing software. Your first step is to go to <strong>AgilePress -> All Products</strong> on the admin menu and create a product.</p>
<p>After you've created a product, you need to record at least one of the things you want to see out of your product. Without getting too far into the weeds (stories and epics and backlog-items, oh my!), let's just go to <strong>AgilePress -> All Tasks</strong> and add at least one task. If you're product is to build a deck, then you might want tasks for creating a design, purchasing the raw materials, and scheduling time off from work, etc. The least you need to enter for your new task(s) is <strong>Associated Product</strong> (that's the product you just created in the previous step) and <strong>Task Status</strong> (which should probably be "To Do" for now).</p>
<h3>Boards Live on Pages!</h3>
<p>This is the front-end part. As long as you now have a product and at least one task associated with said product, you can jump into the board action. Go to your list of products and copy the shortcode for the Kanban board. Create a new WordPress page, paste in the Kanban shortcode, and view the page. Viola! You have a Kanban board for your product/project.</p>
<p>Go back to the WordPress admin panel and add the rest of your tasks (at least all of the tasks you can think of, for now). From the board notes - from the page view - you can change task settings, make comments, view attachments, and - of course - drag the notes to other columns as your tasks progress.</p>
<p>Now... get to work on that deck! (Or term paper, or software product, or whatever.) And if you get stuck, visit <a href="https://agilepress.io/documentation/" target="_blank">the docs on our web site</a> or <a href="https://agilepress.io/contact/" target="_blank">hit us up with your questions</a>.</p>
<p>Thank you for using AgilePress!</p>
</div>
