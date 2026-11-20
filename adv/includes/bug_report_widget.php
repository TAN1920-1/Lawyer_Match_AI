<style>
    .bug-report-widget {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 9999;
    }
    .bug-toggle {
        width: 60px;
        height: 60px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        outline: none;
        text-decoration: none;
    }
    .bug-toggle:hover {
        transform: scale(1.1);
        background: #c82333;
        color: white;
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    }
    .bug-tooltip {
        position: absolute;
        left: 75px;
        top: 50%;
        transform: translateY(-50%);
        background: #333;
        color: white;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    .bug-report-widget:hover .bug-tooltip {
        opacity: 1;
        visibility: visible;
    }
</style>

<div class="bug-report-widget">
    <a href="report_bug.php" class="bug-toggle">
        <i class="fas fa-bug"></i>
    </a>
    <div class="bug-tooltip">Report a Bug</div>
</div>
