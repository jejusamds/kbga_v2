<?php
$qual_page = isset($qual_page) ? (int) $qual_page : 1;
$list = $db->query(
    "SELECT * FROM df_site_qualification WHERE page_no=:page ORDER BY idx DESC",
    ['page' => $qual_page],
    PDO::FETCH_OBJ
);
?>

<ul>
    <?php foreach ($list as $row): ?>
        <li>
            <table cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td align="center" class="text01_td">
                            <div class="w_con"><span><?= htmlspecialchars($row->f_name, ENT_QUOTES) ?></span></div>
                            <div class="m_con">
                                <div class="text01_con"><span><?= htmlspecialchars($row->f_name, ENT_QUOTES) ?></span>
                                </div>
                                <div class="text02_con"><span>등록번호 <span class="bar">｜</span>
                                        <?= htmlspecialchars($row->f_reg_no, ENT_QUOTES) ?></span></div>
                                <div class="text03_con"><span>주무부처 <span class="bar">｜</span>
                                        <?= htmlspecialchars($row->f_ministry, ENT_QUOTES) ?></span></div>
                            </div>
                        </td>
                        <td align="center" class="text02_td">
                            <span><?= htmlspecialchars($row->f_type, ENT_QUOTES) ?></span>
                        </td>
                        <td align="center" class="text03_td">
                            <span><?= htmlspecialchars($row->f_reg_no, ENT_QUOTES) ?></span>
                        </td>
                        <td align="center" class="text04_td">
                            <span><?= htmlspecialchars($row->f_manage_org, ENT_QUOTES) ?></span>
                        </td>
                        <td align="center" class="text05_td">
                            <span><?= htmlspecialchars($row->f_ministry, ENT_QUOTES) ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
    <?php endforeach; ?>
</ul>