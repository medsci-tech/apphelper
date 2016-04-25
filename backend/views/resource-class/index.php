

    <link rel="stylesheet" href="/css/easyTree.css">
    <script src="/js/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="/js/easyTree.js"></script>
    <style>

    </style>

<div class="col-md-3">
    <h3 class="text-success">目录分类管理</h3>
    <div class="easy-tree">
        <ul>
            <li>药店培训</li>
            <li>店长培训</li>
            <li>学院培训
                <ul>
                    <li>第一章</li>
                    <li>Example 2
                        <ul>
                            <li>第一节</li>
                            <li>第二节</li>
                            <li>第三节</li>
                            <li>第四节</li>
                        </ul>
                    </li>
                    <li>Example 3</li>
                    <li>Example 4</li>
                </ul>
            </li>
            <li>Example 0
                <ul>
                    <li>Example 1</li>
                    <li>Example 2</li>
                    <li>Example 3</li>
                    <li>Example 4
                        <ul>
                            <li>Example 1</li>
                            <li>Example 2</li>
                            <li>Example 3</li>
                            <li>Example 4</li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<script>
    (function ($) {
        function init() {
            $('.easy-tree').EasyTree({
                addable: true,
                editable: true,
                deletable: true
            });
        }
        window.onload = init();
    })(jQuery)
</script>
