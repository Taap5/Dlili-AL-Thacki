<!-- زر الطوارئ العائم -->
<button id="emergencyBtn" class="emergency-floating-btn" title="أقرب مستشفى - حالات الطوارئ">
    <i class="fas fa-ambulance"></i>
    <span class="emergency-text">طوارئ</span>
</button>

<style>
    .emergency-floating-btn {
        position: fixed;
        bottom: 30px;
        left: 30px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc3545, #c82333);
        border: none;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        animation: pulse 2s infinite;
    }

    .emergency-floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.5);
    }

    .emergency-floating-btn i {
        font-size: 28px;
    }

    .emergency-text {
        font-size: 10px;
        font-weight: bold;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }
        50% {
            box-shadow: 0 4px 25px rgba(220, 53, 69, 0.7);
        }
        100% {
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }
    }

    @media (max-width: 768px) {
        .emergency-floating-btn {
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
        }

        .emergency-floating-btn i {
            font-size: 24px;
        }

        .emergency-text {
            font-size: 9px;
        }
    }
</style>    
