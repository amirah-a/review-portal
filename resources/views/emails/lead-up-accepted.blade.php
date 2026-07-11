<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>RAPP LEAD-UP Programme</title>
</head>

<body>

    <p>
        Dear {{ $application->APL_Parent_Name }},
    </p>

    <h2>Congratulations!</h2>

    <p>
        We are delighted to inform you that your child,
        <strong>
            {{ $application->APL_FName }}
            {{ $application->APL_LName }}
        </strong>,
        has been successfully selected to participate in the
        <strong>
            RAPP LEAD-UP (Leadership, Empowerment, Advancement,
            Development, Upliftment and Progress) Vacation Programme.
        </strong>
    </p>

    <p>
        On behalf of the <strong>Ministry of Sport and Youth Affairs</strong>,
        we warmly welcome your child to this exciting four-week journey of
        learning, leadership, personal development, and fun.
    </p>

    <p>
        Throughout the programme, participants will have the opportunity to
        build new friendships, develop valuable life skills, engage in exciting
        activities, and create lasting memories in a safe and supportive
        environment.
    </p>

    <p>
        Your child is expected to report to their assigned
        <strong>RAPP LEAD-UP Centre</strong> on
        <strong>Monday 13th July 2026</strong>
        at the designated start time of
        <strong>9:00 a.m.</strong>
    </p>

    <p>
        <strong>Assigned Centre:</strong><br>
        {{ $application->APL_Programme_Center }}
    </p>

    <p>
        We thank you for your confidence in the Programme and for entrusting us
        with the opportunity to contribute to your child's growth and
        development. We look forward to partnering with you to make this a
        rewarding and memorable experience.
    </p>

    <p>
        Once again, congratulations! We look forward to welcoming your child on
        Monday and beginning this exciting journey together.
    </p>

    <p>
        <strong>
            Empowering Our Youth, Strengthening Our Nation.
        </strong>
    </p>

    <p>
        Kind Regards,<br><br>

        <strong>
            National Service Unit<br>
            Ministry of Sport and Youth Affairs
        </strong>
    </p>

</body>

</html>
