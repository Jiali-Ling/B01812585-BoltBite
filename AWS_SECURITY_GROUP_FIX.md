# AWS Security Group Configuration Guide

## Problem
If you see "ERR_CONNECTION_TIMED_OUT" or "响应时间过长" (Response time too long) when accessing the website, it's likely because AWS Security Group is not configured correctly.

## Solution: Configure AWS Security Group

### Step 1: Access AWS EC2 Console
1. Go to: https://console.aws.amazon.com/ec2/
2. Sign in with your AWS account
3. Make sure you're in the correct region (where your EC2 instance is located)

### Step 2: Find Your EC2 Instance
1. In the left sidebar, click **"Instances"**
2. Find and select your EC2 instance (the one running BoltBite)

### Step 3: Open Security Group
1. In the instance details panel at the bottom, find the **"Security"** tab
2. Click on the **Security Group name** (it will be something like `sg-xxxxx`)

### Step 4: Edit Inbound Rules
1. In the Security Group page, click **"Edit inbound rules"** button
2. Click **"Add rule"** button

### Step 5: Add HTTP Rule
Add the following rule:
- **Type:** HTTP
- **Protocol:** TCP
- **Port range:** 80
- **Source:** 0.0.0.0/0
- **Description:** Allow HTTP from anywhere

### Step 6: Add HTTPS Rule
Add another rule:
- **Type:** HTTPS
- **Protocol:** TCP
- **Port range:** 443
- **Source:** 0.0.0.0/0
- **Description:** Allow HTTPS from anywhere

### Step 7: Save Rules
1. Click **"Save rules"** button
2. Wait 1-2 minutes for changes to take effect

### Step 8: Verify
1. Clear browser cache: `Ctrl + Shift + R` (Windows/Linux) or `Cmd + Shift + R` (Mac)
2. Try accessing: `https://b01812585-uws24.duckdns.org`
3. If you see a security warning (self-signed certificate), click **"Advanced"** → **"Proceed to site"**

## Important Notes
- **Source 0.0.0.0/0** means "allow from anywhere" - this is necessary for public web access
- Changes take effect immediately, but may take 1-2 minutes to propagate
- If you still can't access after configuring, wait a few minutes and try again
- Make sure your EC2 instance is **running** (not stopped)
