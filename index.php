<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu - Sales & Form Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #001f54 0%, #a30000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 50px;
        }

        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-top: 40px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 20px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-contract {
            background: linear-gradient(135deg, #a30000 0%, #c70734 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(163, 0, 0, 0.4);
        }

        .btn-contract:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(163, 0, 0, 0.5);
        }

        .btn-sales {
            background: linear-gradient(135deg, #001f54 0%, #003080 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 31, 84, 0.4);
        }

        .btn-sales:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0, 31, 84, 0.5);
        }

        .icon {
            margin-right: 12px;
            font-size: 1.5rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
        }

        .footer {
            margin-top: 40px;
            color: #999;
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            .container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .btn {
                padding: 18px 30px;
                font-size: 1.1rem;
            }
        }

        /* Loading animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeIn 0.6s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="form_contract/Images/Facility.png" alt="Prime Facility Services Logo">
        </div>
        <h1>Welcome</h1>
        <p class="subtitle">Select the application you want to access</p>

        <div class="buttons-container">
            <a href="form_contract/" class="btn btn-contract">
                <span class="icon">📄</span>
                Form for Contract
            </a>

            <a href="sales/" class="btn btn-sales">
                <span class="icon">💼</span>
                Sales
            </a>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> - Prime Facility Services Group</p>
        </div>
    </div>
</body>
</html>
