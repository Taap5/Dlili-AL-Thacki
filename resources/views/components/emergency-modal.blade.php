<!-- مودال أقرب المستشفيات -->
<div id="emergencyModal" class="emergency-modal">
    <div class="emergency-modal-content">
        <div class="emergency-modal-header">
            <div class="emergency-modal-title">
                <i class="fas fa-ambulance"></i>
                <h3>أقرب المستشفيات</h3>
            </div>
            <button class="emergency-modal-close" onclick="closeEmergencyModal()">&times;</button>
        </div>
        <div class="emergency-modal-body">
            <div id="emergencyUserLocation" class="emergency-user-location">
                <i class="fas fa-location-dot"></i>
                <span>جاري تحديد موقعك...</span>
            </div>
            <div id="emergencyResults" class="emergency-results">
                <div class="emergency-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>جاري البحث عن أقرب المستشفيات...</p>
                </div>
            </div>
        </div>
        <div class="emergency-modal-footer">
            <button class="emergency-refresh-btn" onclick="refreshEmergencyLocation()">
                <i class="fas fa-sync-alt"></i> تحديث موقعي
            </button>
        </div>
    </div>
</div>

<style>
    /* تنسيقات المودال */
    .emergency-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .emergency-modal.show {
        display: flex;
    }

    .emergency-modal-content {
        background: white;
        border-radius: 28px;
        width: 90%;
        max-width: 550px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .emergency-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #dc3545, #c82333);
        border-radius: 28px 28px 0 0;
    }

    .emergency-modal-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .emergency-modal-title i {
        font-size: 28px;
        color: white;
    }

    .emergency-modal-title h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: white;
    }

    .emergency-modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        font-size: 24px;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .emergency-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .emergency-modal-body {
        padding: 24px;
    }

    .emergency-user-location {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-right: 4px solid #dc3545;
    }

    .emergency-user-location i {
        font-size: 20px;
        color: #dc3545;
    }

    .emergency-user-location span {
        color: #1a2c3e;
        font-size: 0.85rem;
    }

    .emergency-results {
        max-height: 400px;
        overflow-y: auto;
    }

    .emergency-loading {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .emergency-loading i {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .emergency-hospital-card {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 16px;
        transition: all 0.2s;
        border: 1px solid #f0f0f0;
    }

    .emergency-hospital-card:hover {
        background: #ffffff;
        border-color: #dc3545;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
    }

    .hospital-rank {
        display: inline-block;
        width: 28px;
        height: 28px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-size: 12px;
        font-weight: bold;
        margin-left: 12px;
    }

    .hospital-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a2c3e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hospital-details {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 12px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .hospital-details span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .hospital-distance {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .hospital-actions {
        display: flex;
        gap: 12px;
        margin-top: 12px;
    }

    .hospital-actions .btn-outline-danger {
        background: transparent;
        border: 1px solid #dc3545;
        border-radius: 30px;
        padding: 6px 16px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #dc3545;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .hospital-actions .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
    }

    .hospital-actions .btn-outline-primary {
        background: transparent;
        border: 1px solid #2f3e9e;
        border-radius: 30px;
        padding: 6px 16px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #2f3e9e;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .hospital-actions .btn-outline-primary:hover {
        background: #2f3e9e;
        color: white;
    }

    .emergency-modal-footer {
        padding: 16px 24px 24px;
        border-top: 1px solid #f0f0f0;
    }

    .emergency-refresh-btn {
        width: 100%;
        background: transparent;
        border: 1px solid #dc3545;
        border-radius: 40px;
        padding: 10px;
        color: #dc3545;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .emergency-refresh-btn:hover {
        background: #dc3545;
        color: white;
    }

    @media (max-width: 576px) {
        .emergency-modal-content {
            width: 95%;
            max-height: 90vh;
        }

        .hospital-details {
            flex-direction: column;
            gap: 6px;
        }

        .hospital-actions {
            flex-direction: column;
        }

        .hospital-actions .btn-outline-danger,
        .hospital-actions .btn-outline-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
