app.post('/register', async (req, res) => {
    const { name, email, password, role } = req.body; // Role is included in the form
    const hashedPassword = await bcrypt.hash(password, 10);

    try {
        const newUser = await User.create({ name, email, password: hashedPassword, role });
        res.redirect('/dashboard');  // Redirect to the dashboard or homepage
    } catch (error) {
        console.error(error);
        res.status(500).send("Registration failed.");
    }
});


//check user role

function checkRole(role) {
    return function(req, res, next) {
        // Check if the user's role matches the required role
        if (req.user && req.user.role === role) {
            return next();  // User has the required role
        }
        return res.status(403).send('Forbidden');  // User does not have the required role
    };
}

// Example of a route for team captains only
app.post('/create-team', checkRole('captain'), async (req, res) => {
    const { teamName, teamDescription, userId } = req.body;

    const newTeam = await Team.create({
        name: teamName,
        description: teamDescription,
        createdBy: userId,
    });

    await TeamMember.create({
        userId,
        teamId: newTeam.id,
    });

    res.redirect('/team-dashboard');
});

//login route
app.post('/login', async (req, res) => {
    const { email, password } = req.body;
    
    const user = await User.findOne({ where: { email } });
    
    if (user && await bcrypt.compare(password, user.password)) {
        req.session.user = user;  // Store user data in session
        res.redirect('/dashboard');  // Redirect to the dashboard or home page
    } else {
        res.status(400).send('Invalid login credentials.');
    }
});

