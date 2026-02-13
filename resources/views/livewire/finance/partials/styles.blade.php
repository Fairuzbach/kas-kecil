<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@400;500;600;700&display=swap');

    .financial-dashboard {
        font-family: 'Outfit', sans-serif;
        max-width: 100%;
        margin: 0 auto;
    }

    .dashboard-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        border-radius: 24px;
        padding: 48px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(99, 102, 241, 0.1);
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
    }

    /* Total Realisasi Section */
    .realisasi-section {
        text-align: center;
        margin-bottom: 48px;
        padding: 40px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        border-radius: 20px;
        border: 1px solid rgba(99, 102, 241, 0.1);
        position: relative;
    }

    .realisasi-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 20px;
    }

    .realisasi-amount {
        font-family: 'Playfair Display', serif;
        font-size: 52px;
        font-weight: 700;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        margin-bottom: 16px;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .realisasi-meta {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 10px 24px;
        background: white;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    .meta-dot {
        width: 6px;
        height: 6px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 50%;
    }

    /* Departemen Terboros Section */
    .departemen-section {
        background: white;
        border-radius: 20px;
        padding: 36px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .departemen-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #ef4444 0%, #f97316 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
        transform-origin: top;
    }

    .departemen-section:hover {
        border-color: #6366f1;
        box-shadow: 0 12px 32px rgba(99, 102, 241, 0.15);
        transform: translateY(-4px);
    }

    .departemen-section:hover::before {
        transform: scaleY(1);
    }

    .departemen-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .departemen-title {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fire-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .highest-badge {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%);
        color: #dc2626;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: 1.5px solid rgba(239, 68, 68, 0.3);
    }

    /* Department Content */
    .departemen-content {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 32px;
        align-items: center;
    }

    .dept-info {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .dept-name {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .dept-meta {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .calendar-icon {
        width: 18px;
        height: 18px;
        opacity: 0.6;
    }

    .dept-amount-box {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        padding: 28px 36px;
        border-radius: 16px;
        border: 1.5px solid rgba(99, 102, 241, 0.2);
        text-align: right;
        position: relative;
        min-width: 300px;
    }

    .dept-amount-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .arrow-up {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        animation: bounceArrow 1.5s infinite;
    }

    @keyframes bounceArrow {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .arrow-up svg {
        width: 16px;
        height: 16px;
        color: white;
    }

    .dept-amount-value {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
    }

    .empty-icon {
        font-size: 72px;
        opacity: 0.3;
        margin-bottom: 20px;
        filter: grayscale(1);
    }

    .empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 15px;
        color: #94a3b8;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-card {
            padding: 28px 20px;
        }

        .realisasi-section {
            padding: 28px 20px;
        }

        .realisasi-amount {
            font-size: 38px;
        }

        .departemen-section {
            padding: 24px 20px;
        }

        .departemen-content {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .dept-name {
            font-size: 24px;
        }

        .dept-amount-box {
            min-width: auto;
            text-align: center;
        }

        .dept-amount-label {
            justify-content: center;
        }
    }
</style>
